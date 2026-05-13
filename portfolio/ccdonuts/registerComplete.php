<?php
require_once __DIR__ . '/app/db.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
}

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$name = $_POST['name'];
$furigana = $_POST['furigana'];
$postcode = $_POST['postcode_a'] . '-'. $_POST['postcode_b'];
$address = $_POST['address'];
$email = $_POST['email'];
// パスワードハッシュ化用
// $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
$password_hash = $_POST['password']; // パスワード保存処理確認用


$sql=$pdo->prepare('insert into customers (name, furigana, postcode, address, email, password_hash) values (?, ?, ?, ?, ?, ?)');
$sql->execute([$name, $furigana, $postcode, $address, $email, $password_hash]);
?>

<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="login.php">＞ログイン</a></li>
    <li><a href="registerInput.php">＞会員登録</a></li>
    <li><a href="registerConfirm.php">＞入力確認</a></li>
    <li aria-current="page">＞会員登録完了</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
        
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>会員登録完了</span></h2>

            <div class="completeMessageBox">
                <p class="completeMessageText">会員登録が完了しました。</p>
                <a href="login.php" class="completeLink">
                    <p class="completeMessageText">ログインページへお進みください。</p>
                </a>
            </div>

            <div class="completeLinkWrap">
                <p><a href="login.php" class="completeLink">ログインページへすすむ</a></p>
            </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>