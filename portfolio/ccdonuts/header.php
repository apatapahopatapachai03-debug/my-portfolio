<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/app/functions.php';

?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        
        <?php if (!empty($noindex)) : ?>
        <meta name="robots" content="noindex">
        <?php endif; ?>
        
        <link rel="stylesheet" href="common/reset.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100..900&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="styles/style.css">
        <title>C.C.Donuts</title>
    </head>
    <body id="topArea">
        <header>
            <div class="headerInnerWrap">
                <div class="navIconWrap">
                    
                    <div class="hamburger" id="open_nav"> <!-- ドロワーdiv & アイコン -->
                        <img class="drawerIcon" src="images/drawerIcon.png" alt="ドロワーアイコン">
                    </div>
                    <h1>
                        <a href="index.php"><img class="mainLogo" src="images/mainLogo.svg" alt="C.C.Donuts"></a>
                    </h1>

                    <div class="headerIconWrap">
                        <?php if (isset($_SESSION['customer'])) : ?>
                            <div>
                                <a href="logout.php" class="headerIconLink" onclick="return confirmLogout();">
                                    <img class="logo" src="images/logoutIcon.svg" alt="ログアウト">
                                    <span>ログアウト</span>
                                </a>
                            </div>
                        <?php else : ?>
                            <div>
                                <a href="login.php" class="headerIconLink">
                                    <img class="logo" src="images/loginIcon.svg" alt="ログイン">
                                    <span>ログイン</span>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div>
                            <a href="cart.php" class="headerIconLink">
                                <img class="logo" src="images/cartLog.svg" alt="カート">
                                <span>カート</span>
                            </a>
                        </div>
                    </div> <!-- .headerIconWrap -->
                </div> <!-- .navIconWrap -->
                <!-- 虫眼鏡 & 検索BOXエリア -->
            </div>
                
                <div class="searchArea">
                    <form class="searchWrap" action="productList.php" method="get">
                        <button class="searchLensArea">
                            <img class="searchLens" src="images/lensIcon.svg" alt="検索アイコン">
                        </button>
                        <input class="searchBox" type="text" name="keyword"
                        placeholder="商品名で検索" value="<?= h($_GET['keyword'] ?? '') ?>">
                    </form>
                </div>
                
        </header>

        <!-- ドロワーメニュー -->
        <nav id="nav">
            <div class="inDrawerLogoWrap">
                <img class="inDrawerLOgo" src="images/mainLogo.svg">
                <img class="DrawerCloseBtn" id="closeNav" src="images/drawerCloseBtn.png">
            </div>
            <ul>
                <li><a href="index.php">TOP</a></li>
                <li><a href="productList.php">商品一覧</a></li>
                <?php if (isset($_SESSION['customer'])) : ?>
                    <li><a href="mypage.php">マイページ</a></li>
                    <li><a href="logout.php">ログアウト</a></li>
                <?php else : ?>
                    <li><a href="login.php">ログイン</a></li>
                    <li><a href="registerInput.php">会員登録</a></li>
                <?php endif; ?>
                <li><a href="../index.html">ポートフォリオに戻る</a></li>
                <li><a href="faq.php">よくある質問</a></li>
                <li><a href="contact.php">お問い合わせ</a></li>
                <li><a href="policy.php">当サイトのポリシー</a></li>
            </ul>
        </nav>