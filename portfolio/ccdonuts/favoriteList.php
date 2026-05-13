<?php
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';   // DB接続のためのファイル読み込み

$noindex = true;
$customer_id = $_SESSION['customer']['id'];

$stmt = $pdo->prepare('
SELECT 
    products.id,
    products.name,
    products.price,
    products.introduction,
    products.image_path,
    products.status
FROM favorites
JOIN products ON favorites.product_id = products.id
WHERE favorites.customer_id = ?
AND products.status IN ("active", "coming_soon")
ORDER BY favorites.created_at DESC
');
$stmt->execute([$customer_id]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'header.php';   // ヘッダーファイル読み込み
?>

<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="mypage.php">＞マイページ</a></li>
    <li aria-current="page">＞お気に入り</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>

<section class="productListSection">
    <div class="innerWrap pageTopWide">
        <h2 class="sectionTitle"><span>お気に入り一覧</span></h2>

        <?php if (empty($favorites)) : ?>
            <p class="noResultText">お気に入り登録された商品はありません。</p>
        <?php else : ?>
            <ul class="productCardList">
                <?php foreach ($favorites as $product) : ?>
                    <?php $image = getProductImage($product); ?>

                    <li class="productCard">
                        <!-- statusが「active」販売中の場合は、商品詳細ページへのリンクを有効にする -->
                        <?php if ($product['status'] === 'active') : ?> 
                        <!-- 画像の表示 -->
                        <a href="productDetails.php?id=<?= h($product['id']) ?>">
                            <img src="<?= h($image) ?>" alt="<?= h($product['name']) ?>">
                        </a>
                        <?php else : ?>
                            <img src="<?= h($image) ?>" alt="<?= h($product['name']) ?>">
                        <?php endif; ?>
                            
                        <h3 class="productName"><?= h($product['name']) ?></h3>
                        <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>
                        <!-- statusが「active」の場合は、カートに入れるボタンを表示する -->
                        <?php if ($product['status'] === 'active') : ?>
                            <form class="formWrap" action="app/cartAdd.php" method="post">
                                <input type="hidden" name="quantity" value="1">
                                <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                                <button type="submit" class="cartBtn">カートに入れる</button>
                            </form>
                        <?php else : ?>
                            <p class="comingSoonText">ただいま準備中です..</p>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</section>
</main>
<?php require 'footer.php'; ?>