<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../productList.php');
    exit;
}

require_once __DIR__ . '/db.php';

$customer_id = $_SESSION['customer']['id'];
$product_id = getIntParam($_POST, 'product_id');
$quantity = getIntParam($_POST, 'quantity');

// 商品IDと数量が正の整数でない場合は商品一覧にリダイレクト
if ($product_id === null || $quantity === null || $product_id < 1 || $quantity < 1) {
    header('Location: ../productList.php');
    exit;
}

// 商品が存在し、販売中(active)か確認
$product_sql = $pdo->prepare('SELECT * FROM products WHERE id = ? AND status = ?');
$product_sql->execute([$product_id, 'active']);
$product = $product_sql->fetch(PDO::FETCH_ASSOC);

// 商品が存在しない、または販売中でない場合は商品一覧にリダイレクト
if (!$product) {
    header('Location: ../productList.php');
    exit;
}

// ユーザーのカートテーブルから、該当ユーザーIDと商品IDの行を取得
$sql = $pdo->prepare('select quantity from cart where customer_id = ? and product_id = ?');
$sql->execute([$customer_id, $product_id]);
// 該当する行が存在する場合は数量を更新、存在しない場合は新規行を挿入
$item = $sql->fetch(PDO::FETCH_ASSOC);
if ($item) {
    $sql = $pdo->prepare('update cart set quantity = quantity + ? where customer_id = ? and product_id = ?');
    $sql->execute([$quantity, $customer_id, $product_id]);
} else {
    $sql = $pdo->prepare('insert into cart (customer_id, product_id, quantity) values (?, ?, ?)');
    $sql->execute([$customer_id, $product_id, $quantity]);
}

header('Location: ../cart.php');
exit;