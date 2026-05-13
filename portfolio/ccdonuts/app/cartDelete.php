<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];
$product_id = getIntParam($_POST, 'product_id');

if ($product_id === null) {
    header('Location: ../cart.php');
    exit;
}

$sql = $pdo->prepare('
    DELETE FROM cart
    WHERE customer_id = ?
    AND product_id = ?
');

$sql->execute([$customer_id, $product_id]);

header('Location: ../cart.php');
exit;