<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

unset($_SESSION['customer']);

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;
require 'header.php';
?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="login.php">＞ログイン</a></li>
    <li aria-current="page">＞ログアウト</li>
  </ol>
</nav>

<main>
<?php require 'welcome.php'; ?>

<section class="completeSection">
    <div class="innerWrap pageSidePadding pageTopWide">
        <h2 class="sectionTitle"><span>ログアウト完了</span></h2>

        <div class="completeMessageBox">
            <p class="completeMessageText">ログアウトが完了しました。</p>
            <p class="completeMessageText">ご利用ありがとうございました。</p>
        </div>

        <div class="completeLinkWrap">
            <p><a href="login.php" class="completeLink">ログインページへすすむ</a></p>
            <p><a href="index.php" class="completeLink">TOPページへすすむ</a></p>
        </div>
    </div>
</section>
</main>
<?php require 'footer.php'; ?>