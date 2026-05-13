<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../productList.php');
    exit;
}
$product_id = getIntParam($_POST, 'product_id');

if ($product_id === null) {
    header('Location: ../productList.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

// すでにお気に入り済みか確認
$stmt = $pdo->prepare('
    SELECT id
    FROM favorites
    WHERE customer_id = ?
    AND product_id = ?
');
$stmt->execute([$customer_id, $product_id]);
$favorite = $stmt->fetch(PDO::FETCH_ASSOC);

if ($favorite) {
    // すでにあるなら解除
    $stmt = $pdo->prepare('
        DELETE FROM favorites
        WHERE customer_id = ?
        AND product_id = ?
    ');
    $stmt->execute([$customer_id, $product_id]);
} else {
    // ないなら追加
    $stmt = $pdo->prepare('
        INSERT INTO favorites (customer_id, product_id)
        VALUES (?, ?)
    ');
    $stmt->execute([$customer_id, $product_id]);
}

header('Location: ../productDetails.php?id=' . urlencode($product_id));
exit;