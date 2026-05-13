<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$name = trim($_POST['name']);
$furigana = trim($_POST['furigana']);
$postcode_a = trim($_POST['postcode_a']);
$postcode_b = trim($_POST['postcode_b']);
$address = trim($_POST['address']);
$email = trim($_POST['email']);
$email_confirm = trim($_POST['email_confirm']);
$password = trim($_POST['password']);
$password_confirm = trim($_POST['password_confirm']);

$errors = [];

// 各種チェック
/* 未入力チェック */
if ($name === '') {
    $errors[] = 'お名前を入力してください。';
}
if ($furigana === '') {
    $errors[] = 'お名前（フリガナ）を入力してください。';
}
if ($postcode_a === '') {
    $errors[] = '郵便番号の前半3桁を入力してください。';
}
if ($postcode_b === '') {
    $errors[] = '郵便番号の後半4桁を入力してください。';
}
if ($address === '') {
    $errors[] = 'ご住所を入力してください。';
}

if ($email === '') {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'メールアドレスの形式が正しくありません。';
} // 合わせてみた

if ($email_confirm === '') {
    $errors[] = 'メールアドレス確認用を入力してください。';
}
if ($password === '') {
    $errors[] = 'パスワードを入力してください。';
}
if ($password_confirm === '') {
    $errors[] = 'パスワード確認用を入力してください。';
}

/* 文字数チェック */
if ($name !== '' && mb_strlen($name) > 100) {
    $errors[] = 'お名前は100文字以内で入力してください。';
}
if ($furigana !== '' && mb_strlen($furigana) > 100) {
    $errors[] = 'お名前（フリガナ）は100文字以内で入力してください。';
}
if ($address !== '' && mb_strlen($address) > 200) {
    $errors[] = '住所は200文字以内で入力してください。';
}

/* 正規表現チェック */
if ($furigana !== '' && !preg_match('/^[ァ-ヶー　 ]+$/u', $furigana)) {
    $errors[] = 'お名前（フリガナ）は全角カタカナで入力してください。';
}
if ($postcode_a !== '' && !preg_match('/^\d{3}$/', $postcode_a)) {
    $errors[] = '郵便番号の前半3桁を正しく入力してください。';
}
if ($postcode_b !== '' && !preg_match('/^\d{4}$/', $postcode_b)) {
    $errors[] = '郵便番号の後半4桁を正しく入力してください。';
}

if ($password !== '' && (!preg_match('/^[A-Za-z0-9]+$/', $password) || strlen($password) < 8 || strlen($password) > 20)) {
    $errors[] = 'パスワードは半角英数字8文字以上20文字以内で入力してください。記号の使用はできません。';
}

/* 一致チェック */
if ($email !== '' && $email_confirm !== '' && $email !== $email_confirm) {
    $errors[] = 'メールアドレスが一致しません。';
}
if ($password !== '' && $password_confirm !== '' && $password !== $password_confirm) {
    $errors[] = 'パスワードとパスワード確認用が一致しません。';
}
?>

<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
    <ol>
        <li><a href="index.php">TOP</a></li>
        <li><a href="login.php">＞ログイン</a></li>
        <li><a href="registerInput.php">＞会員登録</a></li>
        <li aria-current="page">＞入力確認</li>
    </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>

     <!-- 確認画面表示 -->
    <?php if (!empty($errors)) : ?>
        <section class="confirmSection">
            <div class="innerWrap pageTopWide">
                <h2 class="sectionTitle"><span>入力エラー</span></h2>
                <ul>
                    <?php foreach ($errors as $error) : ?>
                        <li><?= h($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p><a href="registerInput.php">入力画面に戻る</a></p>
            </div>
        </section>

    <?php else : ?>
        <section class="confirmSection">
            <div class="innerWrap pageTopWide">
                <h2 class="sectionTitle"><span>入力情報確認</span></h2>

                <div class="confirmList">
                    <div class="confirmGroup">
                        <p class="confirmLabel">お名前</p>
                        <p class="confirmText"><?= h($name) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">お名前（フリガナ）</p>
                        <p class="confirmText"><?= h($furigana) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">郵便番号</p>
                        <p class="confirmText"><?= h($postcode_a) ?>-<?= h($postcode_b) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">住所</p>
                        <p class="confirmText"><?= h($address) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">メールアドレス</p>
                        <p class="confirmText"><?= h($email) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">メールアドレス確認用</p>
                        <p class="confirmText"><?= h($email_confirm) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">パスワード</p>
                        <p class="confirmText"><?= h($password) ?></p>
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">パスワード確認用</p>
                        <p class="confirmText"><?= h($password_confirm) ?></p>
                    </div>
                </div>

                <form action="registerComplete.php" method="post">
                    <input type="hidden" name="name" value="<?= h($name) ?>">
                    <input type="hidden" name="furigana" value="<?= h($furigana) ?>">
                    <input type="hidden" name="postcode_a" value="<?= h($postcode_a) ?>">
                    <input type="hidden" name="postcode_b" value="<?= h($postcode_b) ?>">
                    <input type="hidden" name="address" value="<?= h($address) ?>">
                    <input type="hidden" name="email" value="<?= h($email) ?>">
                    <input type="hidden" name="password" value="<?= h($password) ?>">

                    <div class="submitBtnWrap">
                        <button class="memberActionBtn" type="submit">登録する</button>
                    </div>
                </form>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php require 'footer.php'; ?>