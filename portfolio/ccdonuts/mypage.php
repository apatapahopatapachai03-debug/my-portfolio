<?php
require __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];
$stmt = $pdo->prepare('select * from customers where id = ?');
$stmt->execute([$customer_id]);
$customer = $stmt->fetch();
if (!$customer) {
    exit('会員情報が見つかりません。');
}
// カード登録確認
$stmtCard = $pdo->prepare('SELECT * FROM credit_cards WHERE customer_id = ?');
$stmtCard->execute([$customer_id]);
$cards = $stmtCard->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require 'header.php'; ?>
<main>
    <section class="confirmSection">
        <div class="innerWrap pageTopWide">
            <?php if (!empty($_SESSION['flash_message'])) : ?>
            <div class="flashMessage">
                <?= h($_SESSION['flash_message']) ?>
            </div>
            <?php unset($_SESSION['flash_message']); ?>
            <?php endif; ?>
            <h2 class="sectionTitle"><span><?= h($customer['name']) ?> 様のマイページ</span></h2>
            <div class="completeMessageBox">
                <nav class="mypageMenu" aria-label="マイページメニュー">
                    <ul>
                        <li><a href="customerEdit.php">会員情報確認・変更</a></li>
                        <li>
                            <a href="cardInput.php">カード情報登録</a>
                            <div class="mypageCardInfo">
                                <button type="button" class="creditInfoBtn" id="toggleCards">
                                    登録済みクレジットカードを表示
                                </button>

                                <div class="cardListArea" id="cardListArea">
                                    <?php if ($cards) : ?>
                                        <ol class="cardListWrap">
                                            <?php foreach ($cards as $index => $card) : ?>
                                                <li class="cardList textSmall">
                                                    登録カード（<?= $index + 1 ?>）：<?= h($card['card_brand']) ?> 下4桁 <?= h($card['card_last4']) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ol>
                                    <?php else : ?>
                                        <p class="cardListNone">-カード情報は未登録です。</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <li><a href="orderHistory.php">注文履歴</a></li>
                        <li><a href="favoriteList.php">お気に入り商品を見る</a></li>
                        <li><a href="logout.php" onclick="return confirmLogout();">ログアウト</a></li>
                    </ul>
                </nav>
   
            </div>
        </div>
    </section>
</main>
<script src="scripts/script.js"></script>
<?php require 'footer.php'; ?>