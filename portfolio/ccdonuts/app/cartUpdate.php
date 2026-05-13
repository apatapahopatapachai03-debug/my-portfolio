<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['customer'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

$product_id = getIntParam($_POST, 'product_id');
$quantity = getIntParam($_POST, 'quantity');

// product_id と quantity が正しく取得できたか、quantity が1以上か確認
if ($product_id === null || $quantity === null || $quantity < 1) {
    header('Location: ../cart.php');
    exit;
}

$stmt = $pdo->prepare('
    UPDATE cart
    SET quantity = ?
    WHERE customer_id = ?
    AND product_id = ?
');

$stmt->execute([
    $quantity,
    $customer_id,
    $product_id
]);

header('Location: ../cart.php');
exit;