<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

// URLから送られてきた商品IDを取得、IDチェック
$id = getIntParam($_GET, 'id');
// GETパラメータが不正な値ならば商品一覧にリダイレクト
if ($id === null) {
    header('Location: productList.php');
    exit;
}
// 保存した商品IDを元に、DBから該当商品行を取得
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$id]);
// ここでの$productは該当商品行すべてのカラムの値を持つ連想配列
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    // 商品が見つからない場合は商品一覧ページにリダイレクト
    header('Location: productList.php');
    exit;
}

$isFavorite = false;
if (isset($_SESSION['customer'])) {
    $stmt = $pdo->prepare('
        SELECT COUNT(*) 
        FROM favorites 
        WHERE customer_id = ? 
        AND product_id = ?
    ');
    $stmt->execute([
        $_SESSION['customer']['id'],
        $product['id']
    ]);
    // 同じユーザですでにお気に入り済みか確認。存在する場合はCOUNT(*)によりfetchColumn()の戻り値は１となり、true
    $isFavorite = $stmt->fetchColumn() > 0;
}
?>
<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="productList.php">＞商品一覧</a></li>
    <li aria-current="page">＞<?= h($product['name']); ?></li>
  </ol>
</nav>
<main>
<?php require __DIR__ . '/welcome.php'; ?>

    <section class="productDetailsSection">
        <div class="innerWrap">
            <?php $image = getProductImage($product); ?>
            <div class="productDetailsContent">
                <?php if ($product['status'] === 'active') : ?>
                <div class="productImageWrap">
                    <img src="<?= h($image) ?>" alt="<?= h($product['name']) ?>">
                </div>
                <?php else : ?>
                    <img src="<?= h($image) ?>"
                        alt="<?= h($product['name']) ?>">
                <?php endif; ?>

                <h2 class="productDetailsName"><?= h($product['name']) ?></h2>

                <div class="productIntroductionArea">
                    <p class="productIntroduction">
                        <?= nl2br(h($product['introduction'])) ?>
                    </p>
                </div>

                <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>

                <!-- statusが「active」の場合は、カートに入れるボタンを表示する -->
                <?php if ($product['status'] === 'active') : ?>

                    <div class="productActionArea">
                        <form class="productPurchaseArea" action="app/cartAdd.php" method="post">
                            <div class="productCountWrap">
                                <input class="productCountInput" type="number" name="quantity" min="1" value="1">
                                <span class="productCountUnit">個</span>
                            </div>
                            <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                            <button class="addCartButton" type="submit">カートに入れる</button>
                        </form>
                        <!-- お気に入りボタンをjavascriptに変更予定 -->

                        <form class="favoriteForm" action="app/favoriteToggle.php" method="post">
                            <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                            <button
                                class="favoriteButton <?= $isFavorite ? 'isFavorite' : '' ?>"
                                type="submit" aria-label="<?= $isFavorite ? 'お気に入りを解除' : 'お気に入りに追加' ?>">
                               <img class="favoriteIcon"
                                    src="<?= $isFavorite ? 'images/heartIconFilled.svg' : 'images/heartIcon.svg' ?>"
                                    alt=""
                                >
                            </button>
                        </form>
                    </div>

                <?php else : ?>
                    <p class="comingSoonText">ただいま準備中です..</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
<?php require __DIR__ . '/footer.php'; ?>