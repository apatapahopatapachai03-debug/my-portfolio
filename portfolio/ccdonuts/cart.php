<?php
require __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];
$stmt = $pdo->prepare('
    select
        cart.product_id,
        cart.quantity,
        products.name,
        products.price,
        products.image_path,
        products.status
    from cart
    join products on cart.product_id = products.id
    where cart.customer_id = ?
    order by cart.id DESC
');
$stmt->execute([$customer_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <li aria-current="page">＞カート</li>
  </ol>
</nav>
<main>
    <?php require 'welcome.php'; ?>
    <section class="cartSection">
        <div class="container">
            <div class="innerWrap">

                <div class="cartSummaryBox">
                    <p class="cartSummaryCount">現在　商品<?= h($total_quantity) ?>点</p>
                    <p class="cartSummaryPriceText">ご注文小計：<span class="cartSummaryPrice">税込 ¥<?= number_format($total_price) ?></span></p>
                    <a href="purchaseConfirm.php" class="cartConfirmButton">購入確認へ進む</a>
                </div>
                
                <?php if (empty($items)) : ?>
                    <p class="noResultText">カートに商品がありません</p>
                <?php else : ?>
                    <form class="cartClearForm" action="app/cartClear.php" method="post">
                        <button type="submit" class="cartDeleteButton">カートを空にする</button>
                    </form>
                    <?php foreach ($items as $index => $item) : ?>
                        <?php $image = getProductImage($item); ?>
                        <div class="cartItem">
                            <div class="cartItemImageWrap">
                                <img src="<?= h($image) ?>" alt="<?= h($item['name']) ?>">
                            </div>
                            <h2 class="cartItemName"><?= h($item['name']) ?></h2>
                            <p class="cartItemPrice">税込　¥<?= number_format($item['price']) ?></p>

                            <form class="cartUpdateForm" action="app/cartUpdate.php" method="post">
                                <div class="cartItemCountArea">
                                    <label class="cartItemCountLabel" for="cartCount<?= $index + 1 ?>">数量</label>

                                    <input
                                        class="cartItemCountInput"
                                        id="cartCount<?= $index + 1 ?>"
                                        type="number" name="quantity"
                                        value="<?= h($item['quantity']) ?>"
                                        min="1"
                                    >

                                    <span class="cartItemCountUnit">個</span>
                                </div>

                                <input type="hidden" name="product_id" value="<?= h($item['product_id']) ?>">

                                <button class="cartRecalcButton" type="submit">再計算</button>
                            </form>

                            <form action="app/cartDelete.php" method="post">
                                <input type="hidden" name="product_id" value="<?= h($item['product_id']) ?>">
                                <button type="submit" class="cartDeleteButton">削除する</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <div class="cartSummaryBox cartSummaryBoxBottom">
                    <p class="cartSummaryCount">現在　商品<?= h($total_quantity) ?>点</p>
                    <p class="cartSummaryPriceText">ご注文小計：<span class="cartSummaryPrice">税込 ¥<?= number_format($total_price) ?></span></p>
                    <a href="purchaseConfirm.php" class="cartConfirmButton">購入確認へ進む</a>
                </div>

                <div class="submitBtnWrap">
                    <a href="productList.php" class="cartContinueButton">買い物を続ける</a>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>