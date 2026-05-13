<?php 
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];
// ユーザーのカート情報
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
    order by cart.id desc
');
$stmtCart->execute([$customer_id]);
$inCartItems = $stmtCart->fetchAll(PDO::FETCH_ASSOC);

if (empty($inCartItems)) {
    header('Location: cart.php');
    exit;
}


$stmtCard = $pdo->prepare('
    SELECT * FROM credit_cards
    WHERE customer_id = ?
');
$stmtCard->execute([$customer_id]);
$card = $stmtCard->fetch(PDO::FETCH_ASSOC);

if (!$card) {
    header('Location: cardInput.php');
    exit;
}

// トータルの数量と金額を計算
$total_quantity = 0;
$total_price = 0;
foreach ($inCartItems as $item) {
    $total_quantity += $item['quantity'];
    $total_price += $item['price'] * $item['quantity'];
}

try {
    $pdo->beginTransaction();

// オーダーテーブル作成
$stmtOrders = $pdo->prepare('
    INSERT INTO orders (customer_id, total_quantity, total_price)
    VALUES (?, ?, ?)
');
$stmtOrders->execute([$customer_id, $total_quantity, $total_price]);
// オーダーID取得
$order_id = $pdo->lastInsertId();

//別方法
// $stmtOrderId = $pdo->prepare('
//     SELECT id
//     FROM orders
//     WHERE customer_id = ?
//     ORDER BY id DESC
//     LIMIT 1
// ');
// $stmtOrderId->execute([$customer_id]);
// $order = $stmtOrderId->fetch(PDO::FETCH_ASSOC);
// $order_id = $order['id'];


// 注文詳細作成
$stmtDetails = $pdo->prepare('
    INSERT INTO order_details (order_id, product_id, price, quantity)
    VALUES (?, ?, ?, ?)
');
foreach ($inCartItems as $item) {
    $stmtDetails->execute([
        $order_id,
        $item['product_id'],
        $item['price'],
        $item['quantity']
    ]);
}

// 注文処理正常終了後、カートを空にする
$stmtClear = $pdo->prepare('DELETE FROM cart WHERE customer_id = ?');
$stmtClear->execute([$customer_id]);

$pdo->commit();

} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: cart.php');
    exit;
}
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
        
    <section class="completeSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>ご購入完了</span></h2>

            <div class="completeMessageBox sidePaddingReset">
                <p class="completeMessageText">ご購入いただきありがとうございます。</p>
                <p class="completeMessageText">今後ともご愛顧の程、宜しくお願いいたします。</p>
            </div>

            <div class="completeLinkWrap">
                <p><a href="index.php" class="completeLink">TOPページへすすむ</a></p>
            </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>