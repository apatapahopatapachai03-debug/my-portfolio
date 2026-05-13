<!-- カード登録全削除用 -->
<?php
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

$stmt = $pdo->prepare('DELETE FROM credit_cards WHERE customer_id = ?');
$stmt->execute([$customer_id]);

$_SESSION['flash_message'] = 'テスト用のカード情報を削除しました。';
header('Location: purchaseConfirm.php');
exit;