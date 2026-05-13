<?php
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

$productRanking = $pdo->query('
    SELECT 
    products.id,
    products.name,
    products.price,
    products.introduction,
    products.image_path,
    products.status,
    SUM(order_details.quantity) AS total_quantity
    FROM products
    JOIN order_details ON products.id = order_details.product_id
    WHERE products.status = "active"
    GROUP BY
        products.id,
        products.name,
        products.price,
        products.introduction,
        products.image_path,
        products.status
    ORDER BY total_quantity DESC
    LIMIT 6
')->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require 'header.php'; ?>
<main>
<?php
if (isset($_SESSION['customer'])) {
    require 'welcome.php';
}
?>
    <div class="mainVisual"></div>
    <section class="announcementArea">
        <div class="container">
            <div class="pickupArea">
                <div class="topPickupWrap">
                    <a href="productList.php" class="pickupCard">
                        <div class="newBadge">
                            <span class="badgephrase">新商品</span>
                        </div>
                        <img class="newProductImage" src="images/newProductInformationImg.jpg" alt="新商品ドーナツ：サマーシトラス">
                        <p class="textSummercitrus">サマーシトラス</p>
                    </a>
                    <a href="#popularityRankingArea" class="pickupCard">
                        <img class="donutsLifeImage" src="images/donutsLife.jpg" alt="ドーナツを手に取る生活イメージ">
                        <p class="donutsLifeText">ドーナツのある生活</p>
                    </a>
                </div>
                <a href="productList.php" class="bottomPickupCard">
                    <img class="ProductLinkImage" src="images/productListBanner.jpg" alt="商品一覧バナー画像">
                    <p class="productList">商品一覧</p>
                </a>
            </div>
        </div>
    </section>

    <section class="shopIntroductionArea">
        <div class="shopAreaInnerWrap">
            <div class="shopIntroductionText">
                <h2 class="phrase1">philosophy</h2>
                <p class="phrase2">私たちの信念</p>
                <p class="phrase3">"CreatingConnections"</p>
                <p class="phrase4">「ドーナツでつながる」</p>
            </div>
        </div>
    </section>
    <!-- 人気ランキングエリア -->
    <section class="popularityRankingArea" id="popularityRankingArea">
        <div class="innerWrap pageTopWide">
            <h2 class="sectionTitle"><span>人気ランキング</span></h2>
            <?php if (empty($productRanking)) : ?>
                <!-- 集計なし時メッセージ -->
                <p class="noResultText">ランキングは現在集計中です。</p>
            <?php else : ?>
                <ul class="productCardList">
                    <?php foreach ($productRanking as $index => $product) : ?>
                        <?php $image = getProductImage($product); ?>
                        <?php $rank = $index + 1; ?>
                        <li class="productCard">
                            <p class="rankBadge rank<?= h($rank) ?>"><?= h($rank) ?></p>

                            <a href="productDetails.php?id=<?= h($product['id']) ?>">
                                <img src="<?= h($image) ?>" alt="<?= h($product['name']) ?>">
                            </a>

                            <h3 class="productName"><?= h($product['name']) ?></h3>
                            <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>

                            <form class="formWrap" action="app/cartAdd.php" method="post">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                                <button type="submit" class="cartBtn">カートに入れる</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>