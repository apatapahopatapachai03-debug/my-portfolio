<?php 
require __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];
// ユーザーIDを元に、DBから該当ユーザー行を取得
$stmtUser = $pdo->prepare('select * from customers where id = ?');
$stmtUser->execute([$customer_id]);
$customer = $stmtUser->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    exit('会員情報が見つかりません。');
}
// カートテーブルから、該当ユーザーIDの行をすべて取得
$stmtCart = $pdo->prepare('
    select
        cart.product_id,
        cart.quantity,
        products.name,
        products.price
    from cart
    join products on cart.product_id = products.id
    where cart.customer_id = ?
    AND products.status = "active"
    order by cart.id DESC
');
$stmtCart->execute([$customer_id]);
$items = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

if (empty($items)) {
    header('Location: cart.php');
    exit;
}
// クレジットカード登録情報の取得
$stmtCard = $pdo->prepare('select * from credit_cards where customer_id = ?');
$stmtCard->execute([$customer_id]);
$card = $stmtCard->fetch(PDO::FETCH_ASSOC);

// トータルの数量と金額を計算
$total_quantity = 0;
$total_price = 0;
foreach ($items as $item) {
    $total_quantity += $item['quantity'];
    $total_price += $item['price'] * $item['quantity'];
}
?>
<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
      <li><a href="index.php">TOP</a></li>
      <li><a href="cart.php">＞カート</a></li>
      <li aria-current="page">＞購入確認</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
<!-- フラッシュメッセージ -->
<?php if (!empty($_SESSION['flash_message'])) : ?>
    <div class="flashMessage">
        <?= h($_SESSION['flash_message']) ?>
    </div>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>

   <section class="purchaseSection">
        <div class="innerWrap pageTopWide">
            <!-- 購入確認ページ：カート内容・お届け先・支払い方法を表示 -->
            <h2 class="sectionTitle"><span>購入確認</span></h2>
            <div class="purchaseConfirmWrap">   
                    <!-- ご購入商品 -->
                    <div class="purchaseBlock">
                        <h3 class="purchaseBlockTitle">購入商品</h3>
                        <?php foreach ($items as $item) : ?>
                        <div class="purchaseItem">
                            <div class="purchaseRow">
                                <p class="purchaseLabel">商品名</p>
                                <p class="purchaseValue textStrong">
                                    <?= h($item['name']) ?></p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">数量</p>
                                <p class="purchaseValue textStrong"><?= h($item['quantity']) ?>&nbsp;点</p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">小計</p>
                                <p class="purchaseValue textStrong">税込 ￥<?= number_format($item['price'] * $item['quantity']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>   
                        <!-- 合計 -->
                        <div class="purchaseTotalWrap">
                            <div class="purchaseTotalRow">
                                <p class="purchaseLabel">合計数量</p>
                                <p class="purchaseValue textStrong"><?= $total_quantity ?>&nbsp;点</p>
                            </div>
                            <div class="purchaseTotalRow">
                                <p class="purchaseLabel">合計金額</p>
                                <p class="purchaseValue textStrong">税込 ￥<?= number_format($total_price) ?></p>
                            </div>
                        </div>
                    </div>        
                    <!-- お届け先 -->
                    <div class="purchaseBlock">
                        <h3 class="purchaseBlockTitle">お届け先</h3>
                        
                        <div class="purchaseTotalWrap">
                            <div class="purchaseRow">
                                <p class="purchaseLabel">お名前</p>
                                <p class="purchaseValue textStrong"><?= h($customer['name']) ?></p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">郵便番号</p>
                                <p class="purchaseValue textStrong"><?= h($customer['postcode']) ?></p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">住所</p>
                                <p class="purchaseValue textStrong"><?= h($customer['address']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 支払い方法 -->
                <div class="purchaseBlock">
                    <h3 class="purchaseBlockTitle">お支払い方法</h3>
                
                <?php if ($card) : ?>
                     <div class="cardRegistrationGuide">
                        <!-- 支払い方法の中身 -->
                        <div class="purchaseBlock">
                            <div class="purchaseRow">
                                <p class="purchaseLabel">お支払い</p>
                                <p class="purchaseValue textStrong">クレジットカード</p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">ブランド</p>
                                <p class="purchaseValue textStrong"><?= h($card['card_brand']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- ボタン -->
                    <form action="purchaseComplete.php" method="post">
                        <div class="submitBtnWrap">
                            <button type="submit" class="memberActionBtn">購入を確定する</button>
                        </div>
                    </form>
                <?php else: ?>
                <!-- カード情報登録が未登録の場合は、カード登録案内を表示する -->
                <div class="cardRegistrationGuide paddingBottomNarrow">
                    <div class="submitBtnWrap">
                        <a href="cardInput.php" class="memberActionBtn">カード登録</a>
                    </div>
                    <p class="purchaseNotice textBase">
                        カード情報登録がまだのお客様はこちらへお進みください。
                    </p>
                </div>
                 <?php endif; ?>
                 </div>




            </div> <!-- purchaseConfirmWrapend -->
        </div> <!-- innerWrapend -->
    </section>
</main>
<?php require 'footer.php'; ?>