<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cart.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

$stmt = $pdo->prepare('delete from cart where customer_id = ?');
$stmt->execute([$customer_id]);

header('Location: ../cart.php');
exit;
?>