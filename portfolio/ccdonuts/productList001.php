<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }
require 'header.php';
require_once __DIR__ . '/app/db.php';
// $pdo = new PDO(
//     'mysql:host=localhost;dbname=ccdonuts;charset=utf8',
//     'ccStaff',
//     'ccDonuts'
// );

$products = $pdo->query('select * from products order by id')->fetchAll(PDO::FETCH_ASSOC);

// イメージマップ方式
$image_map = [
    1 => 'images/product1.jpg',
    2 => 'images/product2.jpg',
    3 => 'images/product3.jpg',
    4 => 'images/product4.jpg',
    5 => 'images/product5.jpg',
    6 => 'images/product6.jpg',
    7 => 'images/productSet_Fruit1.jpg',
    8 => 'images/productSet_Fruit2.jpg',
    9 => 'images/productSet_Best.jpg',
    10 => 'images/productSet_Chocolate.jpg',
    11 => 'images/productSet_Cream1.jpg',
    12 => 'images/productSet_Cream2.jpg',
];
?>

<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li aria-current="page">＞商品一覧</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>

<section class="productListSection">
    <div class="innerWrap pageTopWide">
        <h2 class="productListTitle">商品一覧</h2>
        
        <!-- 商品イメージ一覧:単品（左から順にID1～6まで） -->
        <section class="menuBlock mainMenuBlock">
            <h3 class="menuBlockTitle">メインメニュー</h3>
            <ul class="productCardList">
                <?php foreach ($products as $product) : ?>
                    <?php if ($product['id'] < 7) : ?>
                        <?php $image = $image_map[$product['id']] ?? 'images/product1.jpg'; ?>
                        <li class="productCard">
                            <a href="productDetails.php?id=<?= h($product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <img src="<?= h($image, ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= h($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                            </a>
                                <p class="productName"><?= h($product['name'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>

                                <form class="formWrap" action="app/cartAdd.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= h($product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="cartBtn">カートに入れる</button>
                                </form>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </section>

            <!-- 商品イメージ一覧:セット（左から順にID7～12まで） -->
            <section class="menuBlock">
                <h3 class="menuBlockTitle">バラエティセット</h3>
                <ul class="productCardList">
                    <?php foreach ($products as $product) : ?>
                        <?php if ($product['id'] >= 7) : ?>
                            <?php $image = $image_map[$product['id']] ?? 'images/ComingSoon.png'; ?>
                            <li class="productCard">
                                <a href="productDetails.php?id=<?= h($product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <img src="<?= h($image, ENT_QUOTES, 'UTF-8') ?>"
                                        alt="<?= h($product['name'], ENT_QUOTES, 'UTF-8') ?>">
                                </a>

                                <p class="productName"><?= h($product['name'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>

                                <form class="formWrap" action="app/cartAdd.php" method="post">
                                    <input type="hidden" name="product_id" value="<?= h($product['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    <button type="submit" class="cartBtn">カートに入れる</button>
                                </form>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </section>

        </div>
    </section>
</main>
<?php require 'footer.php'; ?>