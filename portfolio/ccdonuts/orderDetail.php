<?php 
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/functions.php';

$noindex = true;
$order_id = getIntParam($_GET, 'id');

if ($order_id === null) {
    header('Location: orderHistory.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

$stmtOrder = $pdo->prepare('
    SELECT * FROM orders
    WHERE id = ? AND customer_id = ?
');
$stmtOrder->execute([$order_id, $customer_id]);
$order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    header('Location: orderHistory.php');
    exit;
}
$stmtDetails = $pdo->prepare('
    SELECT
        order_details.product_id,
        order_details.price,
        order_details.quantity,
        products.name
    FROM order_details
    JOIN products ON order_details.product_id = products.id
    
    JOIN orders ON order_details.order_id = orders.id
    WHERE order_details.order_id = ? AND orders.customer_id = ?
');
$stmtDetails->execute([$order_id, $customer_id]);
$details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

// トータルの数量と金額を計算
$total_quantity = 0;
$total_price = 0;
foreach ($details as $detail) {
    $total_quantity += $detail['quantity'];
    $total_price += $detail['price'] * $detail['quantity'];
}
?>

<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="orderHistory.php">＞注文履歴</a></li>
    <li aria-current="page">＞注文詳細</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>

    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>注文詳細</span></h2>

                    <div class="purchaseBlock">
                        <?php foreach ($details as $detail) : ?>
                        <div class="purchaseItem">
                            <div class="purchaseRow">
                                <p class="purchaseLabel">商品名</p>
                                <p class="purchaseValue textStrong">
                                    <?= h($detail['name']) ?></p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">数量</p>
                                <p class="purchaseValue textStrong"><?= h($detail['quantity']) ?>&nbsp;点</p>
                            </div>
                            <div class="purchaseRow">
                                <p class="purchaseLabel">小計</p>
                                <p class="purchaseValue textStrong">税込 ￥<?= number_format($detail['price'] * $detail['quantity']) ?></p>
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
            <div class="completeLinkWrap">
                <p><a href="index.php" class="completeLink">TOPページへ戻る</a></p>
            </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>