<?php 
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/functions.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];

$stmtOrder = $pdo->prepare('select * from orders where customer_id = ?');
$stmtOrder->execute([$customer_id]);
$orders = $stmtOrder->fetchAll(PDO::FETCH_ASSOC);

// foreach ($orders as &$order) {
//     $stmtDetails = $pdo->prepare('
//         select
//             order_details.product_id,
//             order_details.price,
//             order_details.quantity,
//             products.name
//         from order_details
//         join products on order_details.product_id = products.id
//         where order_details.order_id = ?
//     ');
//     $stmtDetails->execute([$order['id']]);
//     $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
//     $order['details'] = $details;
// }
?>
<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="cart.php">＞カート</a></li>
    <li><a href="purchaseConfirm.php">＞購入確認</a></li>
    <li aria-current="page">＞購入完了</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
        

<pre>
<?php
?>
</pre>
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>注文履歴</span></h2>

            <div class="orderHistoryWrap">
                <?php if (empty($orders)) : ?>
                    <p>注文履歴はありません。</p>
                <?php else : ?>
                    <?php foreach ($orders as $order) : ?>
                        <div class="orderHistoryItem">
                            <h3 class="orderDate">注文日: <?= h($order['created_at'], ENT_QUOTES, 'UTF-8') ?></h3>
                            <ul class="orderDetails">
                                <?php foreach ($order['details'] as $detail) : ?>
                                    <li>
                                        <span class="productName"><?= h($detail['name'], ENT_QUOTES, 'UTF-8') ?></span>
                                        <span class="productQuantity"><?= h($detail['quantity'], ENT_QUOTES, 'UTF-8') ?>点</span>
                                        <span class="productPrice">税込 ￥<?= number_format($detail['price'] * $detail['quantity']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <p class="orderTotal">合計金額: 税込 ￥<?= number_format(array_sum(array_map(function($d) { return $d['price'] * $d['quantity']; }, $order['details']))) ?></p>
                        </div>
                        <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="completeLinkWrap">
                <p><a href="index.php" class="completeLink">TOPページへ戻る</a></p>
            </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>