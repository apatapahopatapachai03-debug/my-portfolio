<?php
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';   // DB接続のためのファイル読み込み


// 検索機能でこのページにアクセスしてきた場合、キーワードを取得して商品を検索する
$keyword = mb_convert_kana($_GET['keyword'] ?? '', 's', 'UTF-8'); // 全角スペースを半角スペースに変換
$keyword = trim($keyword); // 前後の空白を削除
$isSearch = $keyword !== '';
if ($keyword !== '') {
    $searchWord = '%' . $keyword . '%';
    // LIKE検索
    $stmt = $pdo->prepare('
    SELECT * FROM products
    WHERE status IN ("active", "coming_soon")
    AND category IN ("main", "set", "box")
    AND (name LIKE ? OR introduction LIKE ?)
    ORDER BY sort_order ASC
    ');
    $stmt->execute([$searchWord, $searchWord]);
    $searchProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
    // メイン、セット、ボックスの各カテゴリーの商品を取得
    $main_products = $pdo->query("
        SELECT * FROM products
        WHERE category = 'main'
        AND status IN ('active', 'coming_soon')
        ORDER BY sort_order ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
    $set_products = $pdo->query("
        SELECT * FROM products
        WHERE category = 'set'
        AND status IN ('active', 'coming_soon')
        ORDER BY sort_order ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
    $box_products = $pdo->query("
        SELECT * FROM products
        WHERE category = 'box'
        AND status IN ('active', 'coming_soon')
        ORDER BY sort_order ASC
    ")->fetchAll(PDO::FETCH_ASSOC);
}
require 'header.php';   // ヘッダーファイル読み込み
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
        <!-- 検索キーワードでヒットした件数を表示する -->
        <?php if ($isSearch) : ?>
            <section class="menuBlock mainMenuBlock">
                <h3 class="menuBlockTitle">
                    「<?= h($keyword) ?>」の検索結果: <?= count($searchProducts) ?>件
                </h3>
                <?php if (empty($searchProducts)) : ?>
                    <p class="searchNoResult">該当する商品はありません。</p>
                <?php else : ?>
                    <!-- ヒットした商品を表示 -->
                    <ul class="productCardList">
                        <?php foreach ($searchProducts as $product) : ?>
                            <?php $image = getProductImage($product); ?>
                            <li class="productCard">
                                <!-- statusが「active」販売中の場合は、商品詳細ページへのリンクを有効にする -->
                                <?php
                                if ($product['status'] === 'active') : ?> 
                                    <!-- リンク付き画像の表示 -->
                                    <a href="productDetails.php?id=<?= h($product['id']) ?>">
                                        <img src="<?= h($image) ?>"
                                            alt="<?= h($product['name']) ?>">
                                    </a>
                                <?php else : ?>
                                    <!-- 画像のみを表示 -->
                                    <img src="<?= h($image) ?>"
                                        alt="<?= h($product['name']) ?>">
                                <?php endif; ?>
                                <!-- 一律表示 -->
                                <p class="productName"><?= h($product['name']) ?></p>
                                <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>

                                <!-- statusが「active」の場合は、カートに入れるボタンを表示する -->
                                <?php if ($product['status'] === 'active') : ?>
                                    <form class="formWrap" action="app/cartAdd.php" method="post">
                                        <input type="hidden" name="quantity" min="1" value="1">
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
            </section>

        <?php else : ?>
            <!-- 検索キーワードがない場合は、通常の商品一覧を表示する -->
    
            <!-- 商品イメージ一覧:単品 -->
            <section class="menuBlock mainMenuBlock">
                <h3 class="menuBlockTitle">メインメニュー</h3>
                <ul class="productCardList">
                    <?php foreach ($main_products as $product) : ?>
                        <?php $image = getProductImage($product); ?>
                        <li class="productCard">
                            <!-- statusが「active」販売中の場合は、商品詳細ページへのリンクを有効にする -->
                            <?php
                            if ($product['status'] === 'active') : ?> 

                            <!-- 画像の表示 -->
                            <a href="productDetails.php?id=<?= h($product['id']) ?>">
                                <img src="<?= h($image) ?>"
                                    alt="<?= h($product['name']) ?>">
                            </a>
                            <?php else : ?>
                                <img src="<?= h($image) ?>"
                                    alt="<?= h($product['name']) ?>">
                            <?php endif; ?>

                            <p class="productName"><?= h($product['name']) ?></p>
                            <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>
                            <!-- statusが「active」の場合は、カートに入れるボタンを表示する -->
                            <?php if ($product['status'] === 'active') : ?>
                                <form class="formWrap" action="app/cartAdd.php" method="post">
                                    <input type="hidden" name="quantity" min="1" value="1">
                                    <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                                    <button type="submit" class="cartBtn">カートに入れる</button>
                                </form>
                            <?php else : ?>
                                <p class="comingSoonText">ただいま準備中です..</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <!-- 商品イメージ一覧:セット -->
            <section class="menuBlock">
                <h3 class="menuBlockTitle">バラエティセット</h3>
                <ul class="productCardList">
                    <?php foreach ($set_products as $product) : ?>
                        <?php $image = getProductImage($product); ?>
                        <li class="productCard">
                            <!-- statusが「active」販売中の場合は、商品詳細ページへのリンクを有効にする -->
                            <?php
                            if ($product['status'] === 'active') : ?> 
                            <a href="productDetails.php?id=<?= h($product['id']) ?>">
                                <img src="<?= h($image) ?>"
                                    alt="<?= h($product['name']) ?>">
                            </a>

                            <?php else : ?>
                                <img src="<?= h($image) ?>"
                                    alt="<?= h($product['name']) ?>">
                            <?php endif; ?>

                            <p class="productName"><?= h($product['name']) ?></p>
                            <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>
                            <!-- statusが「active」の場合は、カートに入れるボタンを表示する -->
                            <?php if ($product['status'] === 'active') : ?>
                                <form class="formWrap" action="app/cartAdd.php" method="post">
                                    <input type="hidden" name="quantity" min="1" value="1">
                                    <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                                    <button type="submit" class="cartBtn">カートに入れる</button>
                                </form>
                            <?php else : ?>
                            <p class="comingSoonText">ただいま準備中です..</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <!-- 商品イメージ一覧:box -->
            <section class="menuBlock">
                <h3 class="menuBlockTitle">ボックスセット</h3>
                <ul class="productCardList">
                    <?php foreach ($box_products as $product) : ?>
                        <?php $image = getProductImage($product); ?>
                        <li class="productCard">
                            <!-- statusが「active」販売中の場合は、商品詳細ページへのリンクを有効にする -->
                            <?php
                            if ($product['status'] === 'active') : ?> 
                            <a href="productDetails.php?id=<?= h($product['id']) ?>">
                                <img src="<?= h($image) ?>"
                                    alt="<?= h($product['name']) ?>">
                            </a>

                            <?php else : ?>
                                <img src="<?= h($image) ?>" 
                                    alt="<?= h($product['name']) ?>">
                            <?php endif; ?>

                            <p class="productName"><?= h($product['name']) ?></p>
                            <p class="productPrice">税込 ￥<?= number_format($product['price']) ?></p>
                            <!-- statusが「active」の場合は、カートに入れるボタンを表示する -->
                            <?php if ($product['status'] === 'active') : ?>
                                <form class="formWrap" action="app/cartAdd.php" method="post">
                                    <input type="hidden" name="quantity" min="1" value="1">
                                    <input type="hidden" name="product_id" value="<?= h($product['id']) ?>">
                                    <button type="submit" class="cartBtn">カートに入れる</button>
                                </form>
                            <?php else : ?>
                            <p class="comingSoonText">ただいま準備中です..</p>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?> 
    </div>
</section>
</main>
<?php require 'footer.php'; ?>