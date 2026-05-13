<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

$noindex = true;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('不正なアクセスです。');
}

$email = trim($_POST['email']);
$password = trim($_POST['password']);
$errors = [];

if ($email === '') {
    $errors[] = 'メールアドレスを入力してください。';
}
if ($password === '') {
    $errors[] = 'パスワードを入力してください。';
}
if (empty($errors)) {
    $sql = $pdo->prepare('select * from customers where email = ?');
    $sql->execute([$email]);
    // customersテーブルから、入力されたメールアドレスに一致する行を取得↓
    $customer = $sql->fetch(PDO::FETCH_ASSOC);

    // パスワードをハッシュ化した場合のハッシュパスワード検証用
    // if ($customer && password_verify($password, $customer['password_hash'])) {
    //     $_SESSION['customer'] = [
    //         'id' => $customer['id'],
    //         'name' => $customer['name']
    //     ];
    // } else {
    //     $errors[] = 'メールアドレスまたはパスワードが正しくありません。';
    // }

        // ハッシュ化されていないバージョン
    if ($customer && $password === $customer['password_hash']) {
        $_SESSION['customer'] = [
            'id' => $customer['id'],
            'name' => $customer['name']
        ];
    } else {
        $errors[] = 'メールアドレスまたはパスワードが正しくありません。';
    }
}
?>
<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="login.php">＞ログイン</a></li>
    <li aria-current="page">＞ログイン結果</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>

<?php if (!empty($errors)) : ?>
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>ログインエラー</span></h2>

            <div class="completeMessageBox">
                <?php foreach ($errors as $error) : ?>
                    <p class="completeMessageText"><?= h($error, ENT_QUOTES, 'UTF-8') ?></p>
                <?php endforeach; ?>
            </div>

            <div class="completeLinkWrap">
                <p><a href="login.php" class="completeLink">ログイン画面に戻る</a></p>
            </div>
        </div>
    </section>
<?php else : ?>
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>ログイン完了</span></h2>

            <div class="completeMessageBox">
                <p class="completeMessageText">ログインが完了しました。</p>
                <p class="completeMessageText">引き続きお楽しみください。</p>
            </div>

            <div class="completeLinkWrap">
                <p><a href="purchaseConfirm.php" class="completeLink">購入確認ページへすすむ</a></p>
                <p><a href="index.php" class="completeLink">TOPページへすすむ</a></p>
            </div>
        </div>
    </section>
<?php endif; ?>
</main>
<?php require 'footer.php'; ?>