<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   header('Location: cardInput.php');
    exit;
}
require __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

$customer_id = $_SESSION['customer']['id'];
$cardName = trim($_POST['card_name'] ?? '');
$cardNumber = trim($_POST['card_number'] ?? '');
$cardBrand = trim($_POST['card_brand'] ?? '');
$expireMonth = trim($_POST['expire_month'] ?? '');
$expireYear = trim($_POST['expire_year'] ?? '');
$securityCode = trim($_POST['security_code'] ?? '');

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
if ($expireMonth === '' || !preg_match('/^(0?[1-9]|1[0-2])$/', $expireMonth)) {
    $errors[] = '有効期限（月）が不正です。';
}
if ($expireYear === '' || !preg_match('/^\d{2}$/', $expireYear)) {
    $errors[] = '有効期限（年）が不正です。';
}
if ($securityCode === '' || !preg_match('/^\d{3}$/', $securityCode)) {
    $errors[] = 'セキュリティコードが不正です。';
}
if ($cardBrand !== 'JCB' && $cardBrand !== 'Visa' && $cardBrand !== 'Mastercard') {
    $errors[] = 'カード会社が不正です。';
}
if (!empty($errors)) {
    exit('不正な入力です。カード入力画面からやり直してください。');
}

// 登録済みチェック
$stmtCheck = $pdo->prepare('
    SELECT id FROM credit_cards 
    WHERE customer_id = ? AND card_last4 = ? AND expiry_year = ? AND expiry_month = ?
');
$stmtCheck->execute([$customer_id, substr($cardNumber, -4), $expireYear, $expireMonth]);
if ($stmtCheck->fetch()) {
    header('Refresh: 3; URL=cardInput.php');
    exit('入力されたカード情報は既に登録されています。3秒後にカード登録画面へ戻ります。');
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
?>
<?php require 'header.php'; ?>

<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="cart.php">＞カート</a></li>
    <li><a href="purchaseConfirm.php">＞購入確認</a></li>
    <li><a href="cardInput.php">＞カード情報</a></li>
    <li><a href="cardConfirm.php">＞情報確認</a></li>
    <li aria-current="page">＞登録完了</li>
  </ol>
</nav>
<main>

<?php require 'welcome.php'; ?>         
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>カード情報登録完了</span></h2>

            <div class="completeMessageBox">
                <p class="completeMessageText">お支払い情報登録が完了しました。</p>
                <p class="completeMessageText">続けて購入確認ページへお進みください。</p>
            </div>

            <div class="completeLinkWrap">
                <p><a href="purchaseConfirm.php" class="completeLink">購入確認ページへすすむ</a></p>
            </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>