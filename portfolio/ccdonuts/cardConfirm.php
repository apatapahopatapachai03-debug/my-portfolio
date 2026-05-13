<?php 
require __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
   header('Location: cardInput.php');
    exit;
}
// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$cardName = trim($_POST['card_name'] ?? '');
$cardNumber = mb_convert_kana(trim($_POST['card_number'] ?? ''), 'n', 'UTF-8');
$cardBrand = trim($_POST['card_brand'] ?? '');
$expireMonth = mb_convert_kana(trim($_POST['expire_month'] ?? ''), 'n', 'UTF-8');
$expireYear = mb_convert_kana(trim($_POST['expire_year'] ?? ''), 'n', 'UTF-8');
$securityCode = mb_convert_kana(trim($_POST['security_code'] ?? ''), 'n', 'UTF-8');
// 月の有効期限の入力が１桁の場合、先頭に0を付与して2桁にする
if ($expireMonth !== '' && preg_match('/^\d{1,2}$/', $expireMonth)) {
    $expireMonth = str_pad($expireMonth, 2, '0', STR_PAD_LEFT);
}
// 年の有効期限の入力が１桁の場合、先頭に0を付与して2桁にする
if ($expireYear !== '' && preg_match('/^\d{1,2}$/', $expireYear)) {
    $expireYear = str_pad($expireYear, 2, '0', STR_PAD_LEFT);
}
// 入力されたカード情報をセッションに保存。途中まで入力された情報を再利用するため。
$_SESSION['old'] = [
    'card_name' => $cardName,
    'card_number' => $cardNumber,
    'card_brand' => $cardBrand,
    'expire_month' => $expireMonth,
    'expire_year' => $expireYear,
    'security_code' => $securityCode,
];

$errors =[];

/********* バリデーションチェック *********/
/********* 必須入力チェック *********/
if ($cardName === '' ) {
    $errors[] = 'お名前を入力してください。';
}
if ($cardNumber === '') {
    $errors[] = 'カード番号を入力してください。';
}
if ($cardBrand === '') {
    $errors[] = 'カード会社を選択してください。';
}
if ($expireMonth === '') {
    $errors[] = '有効期限の月を入力してください。';
}
if ($expireYear === '') {
    $errors[] = '有効期限の年を入力してください。';
}
if ($securityCode === '') {
    $errors[] = 'セキュリティコードを入力してください。';
}

/* 文字数チェック */
if ($cardName !== '' && mb_strlen($cardName) > 100) {
    $errors[] = 'お名前は100文字以内で入力してください。';
}
if ($cardNumber !== '' && mb_strlen($cardNumber) > 16) {
    $errors[] = 'カード番号は16文字以内で入力してください。';
}

/* 正規表現チェック */
if ($cardNumber !== '' && !preg_match('/^\d{13,16}$/', $cardNumber)) {
    $errors[] = 'カード番号は13～16桁の数字で入力してください。';
}
if ($expireMonth !== '' && !preg_match('/^(0?[1-9]|1[0-2])$/', $expireMonth)) {
    $errors[] = '有効期限の月は1～12の数字で入力してください。';
}
if ($expireYear !== '' && !preg_match('/^\d{2}$/', $expireYear)) {
    $errors[] = '有効期限の年は西暦下2桁の数字で入力してください。';
}
if ($securityCode !== '' && !preg_match('/^\d{3}$/', $securityCode)) {
    $errors[] = 'セキュリティコードは3桁の数字で入力してください。';
}
$allowedBrands = ['JCB', 'Visa', 'Mastercard'];
if (!in_array($cardBrand, $allowedBrands, true)) {
    $errors[] = 'カード会社が不正です。';
}

?>
<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="purchaseConfirm.php">＞購入確認</a></li>
    <li><a href="cardInput.php">＞カード情報入力</a></li>
    <li aria-current="page">＞入力情報確認</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
    <?php if (!empty($errors)) : ?>
        <section class="confirmSection">
            <div class="innerWrap pageTopWide">
                <h2 class="sectionTitle"><span>入力エラー</span></h2>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a href="cardInput.php">入力画面に戻る</a></p>
            </div>
        </section>

    <?php else : ?>
        <section class="confirmSection">
            <div class="innerWrap pageSidePadding pageTopWide">
                <h2 class="sectionTitle"><span>入力情報確認</span></h2>

                <div class="confirmList">
                    <div class="confirmGroup">
                        <p class="confirmLabel">お名前</p>
                        <p class="confirmText"><?= h($cardName) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">カード番号</p>
                        <p class="confirmText"><?= h($cardNumber) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">カード会社</p>
                        <p class="confirmText"><?= h($cardBrand) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">有効期限</p>
                        <div class="confirmExpiryWrap">
                            <p class="confirmExpiryRow">
                                <span class="confirmText"><?= h($expireMonth) ?></span>
                                <span class="expiryUnit">月</span>
                            </p>
                            <p class="confirmExpiryRow">
                                <span class="confirmText"><?= h($expireYear) ?></span>
                                <span class="expiryUnit">年</span>
                            </p>
                        </div>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">セキュリティコード</p>
                        <p class="confirmText"><?= h($securityCode) ?></p>
                    </div>
                </div>

                <form action="cardComplete.php" method="post">
                    <input type="hidden" name="card_name" value="<?= h($cardName) ?>">
                    <input type="hidden" name="card_number" value="<?= h($cardNumber) ?>">
                    <input type="hidden" name="card_brand" value="<?= h($cardBrand) ?>">
                    <input type="hidden" name="expire_month" value="<?= h($expireMonth) ?>">
                    <input type="hidden" name="expire_year" value="<?= h($expireYear) ?>">
                    <input type="hidden" name="security_code" value="<?= h($securityCode) ?>">

                    <div class="submitBtnWrap">
                        <button class="memberActionBtn" type="submit">登録する</button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php require 'footer.php'; ?>