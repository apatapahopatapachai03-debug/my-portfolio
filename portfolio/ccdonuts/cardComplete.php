<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cardInput.php');
    exit;
}

require __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';
// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;
$customer_id = $_SESSION['customer']['id'];
$cardName = trim($_POST['card_name'] ?? '');
$cardNumber = mb_convert_kana(trim($_POST['card_number'] ?? ''), 'n', 'UTF-8');
$cardBrand = trim($_POST['card_brand'] ?? '');
$expireMonth = mb_convert_kana(trim($_POST['expire_month'] ?? ''), 'n', 'UTF-8');
$expireYear = mb_convert_kana(trim($_POST['expire_year'] ?? ''), 'n', 'UTF-8');
$securityCode = mb_convert_kana(trim($_POST['security_code'] ?? ''), 'n', 'UTF-8');

if ($expireMonth !== '' && preg_match('/^\d{1,2}$/', $expireMonth)) {
    $expireMonth = str_pad($expireMonth, 2, '0', STR_PAD_LEFT);
}

$errors = [];

if ($cardName === '') {
    $errors[] = 'お名前が未入力です。';
}
if ($cardNumber === '' || !preg_match('/^\d{13,16}$/', $cardNumber)) {
    $errors[] = 'カード番号が不正です。';
}
if ($cardBrand === '') {
    $errors[] = 'カード会社が未選択です。';
}
if ($expireMonth === '' || !preg_match('/^(0[1-9]|1[0-2])$/', $expireMonth)) {
    $errors[] = '有効期限（月）が不正です。';
}
if ($expireYear === '' || !preg_match('/^\d{2}$/', $expireYear)) {
    $errors[] = '有効期限（年）が不正です。';
}
if ($securityCode === '' || !preg_match('/^\d{3}$/', $securityCode)) {
    $errors[] = 'セキュリティコードが不正です。';
}

$allowedBrands = ['JCB', 'Visa', 'Mastercard'];
if (!in_array($cardBrand, $allowedBrands, true)) {
    $errors[] = 'カード会社が不正です。';
}

$_SESSION['old'] = [
    'card_name' => $cardName,
    'card_number' => $cardNumber,
    'card_brand' => $cardBrand,
    'expire_month' => $expireMonth,
    'expire_year' => $expireYear,
    'security_code' => $securityCode,
];

if (!empty($errors)) {
    $_SESSION['flash_error'] = '入力内容を確認してください。';
    header('Location: cardInput.php');
    exit;
}

// 登録済みチェック
$stmtCheck = $pdo->prepare('
    SELECT id FROM credit_cards 
    WHERE customer_id = ? AND card_last4 = ? AND expiry_year = ? AND expiry_month = ?
');
$stmtCheck->execute([$customer_id, substr($cardNumber, -4), $expireYear, $expireMonth]);

if ($stmtCheck->fetch()) {
    $_SESSION['flash_error'] = '入力されたカード情報は既に登録されています。';
    header('Location: cardInput.php');
    exit;
}

$stmt = $pdo->prepare('
    INSERT INTO credit_cards (
        customer_id,
        card_holder_name,
        card_brand,
        card_last4,
        expiry_year,
        expiry_month
    )
    VALUES (?, ?, ?, ?, ?, ?)
');

$result = $stmt->execute([
    $customer_id,
    $cardName,
    $cardBrand,
    substr($cardNumber, -4),
    $expireYear,
    $expireMonth
]);

if (!$result) {
    $_SESSION['flash_error'] = 'カード情報の登録に失敗しました。';
    header('Location: cardInput.php');
    exit;
}

unset($_SESSION['old']);
$_SESSION['flash_message'] = 'カード登録が完了しました。';
header('Location: purchaseConfirm.php');
exit;