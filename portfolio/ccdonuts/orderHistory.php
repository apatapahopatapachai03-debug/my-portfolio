<?php 
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/functions.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];

$stmtOrder = $pdo->prepare('
    SELECT * FROM orders
    WHERE customer_id = ?
    ORDER BY created_at DESC
');
$stmtOrder->execute([$customer_id]);
$orders = $stmtOrder->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li aria-current="page">＞注文履歴</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
        
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>注文履歴</span></h2>

            <div class="orderHistoryWrap">
                <?php if (empty($orders)) : ?>
                    <p class="noResultText">注文履歴はありません。</p>
                <?php else : ?>
                    <?php $no = 1; ?>
                    <?php foreach ($orders as $order) : ?>
                        <div class="purchaseItem">
                            <div class="purchaseRow">
                                <p class="purchaseLabel">注文履歴</p>
                                <p class="purchaseValue textStrong"><?= $no ?>件目</p>
                            </div>

                            <div class="purchaseRow">
                                <p class="purchaseLabel">注文番号</p>
                                <p class="purchaseValue textStrong"><?= h($order['id']) ?></p>
                            </div>

                            <div class="purchaseRow">
                                <p class="purchaseLabel">注文日時</p>
                                <p class="purchaseValue textStrong"><?= h($order['created_at']) ?></p>
                            </div>

                            <div class="purchaseRow">
                                <p class="purchaseLabel">合計点数</p>
                                <p class="purchaseValue textStrong"><?= h($order['total_quantity']) ?>点</p>
                            </div>

                            <div class="purchaseRow">
                                <p class="purchaseLabel">合計金額</p>
                                <p class="purchaseValue textStrong">税込 ￥<?= number_format($order['total_price']) ?></p>
                            </div>

                            <div class="purchaseRow">
                                <p class="purchaseLabel">詳細</p>
                                <p class="purchaseValue textStrong">
                                    <a href="orderDetail.php?id=<?= h($order['id']) ?>" class="completeLink">注文詳細を見る</a>
                                </p>
                            </div>
                            
                        </div>
                        <?php $no++; ?>
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