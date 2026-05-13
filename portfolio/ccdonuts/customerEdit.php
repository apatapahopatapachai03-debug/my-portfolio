<?php
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$customer_id = $_SESSION['customer']['id'];

$stmt = $pdo->prepare('
    SELECT
        id,
        name,
        furigana,
        postcode,
        address,
        email
    FROM customers
    WHERE id = ?
');
$stmt->execute([$customer_id]);
$customer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$customer) {
    exit('会員情報が見つかりません。');
}

// 郵便番号を 123-4567 の形から分割
$postcodeParts = explode('-', $customer['postcode']);
$postcode_a = $postcodeParts[0] ?? '';
$postcode_b = $postcodeParts[1] ?? '';
?>

<?php require 'header.php'; ?>

<nav class="breadCrumb" aria-label="パンくずリスト">
    <ol>
        <li><a href="index.php">TOP</a></li>
        <li><a href="mypage.php">＞マイページ</a></li>
        <li aria-current="page">＞会員情報確認・変更</li>
    </ol>
</nav>

<main>
<?php require 'welcome.php'; ?>

    <section class="confirmSection">
        <div class="innerWrap pageTopWide">
            <h2 class="sectionTitle"><span>会員情報確認・変更</span></h2>

            <form action="app/customerUpdate.php" method="post" class="memberForm">

                <div class="confirmList">
                    <div class="confirmGroup">
                        <label class="confirmLabel" for="name">お名前</label>
                        <input class="confirmText memberInput"
                            type="text"
                            id="name"
                            name="name"
                            value="<?= h($customer['name']) ?>"
                            required
                        >
                    </div>

                    <div class="confirmGroup">
                        <label class="confirmLabel" for="furigana">お名前（フリガナ）</label>
                        <input class="confirmText memberInput"
                            type="text"
                            id="furigana"
                            name="furigana"
                            value="<?= h($customer['furigana']) ?>"
                            required
                        >
                    </div>

                    <div class="confirmGroup">
                        <p class="confirmLabel">郵便番号</p>
                        <div class="postcodeInputWrap">
                            <input class="confirmText memberInput postcodeInputShort"
                                type="text"
                                name="postcode_a"
                                value="<?= h($postcode_a) ?>"
                                maxlength="3"
                                required
                            >
                            <span>-</span>
                            <input class="confirmText memberInput postcodeInputLong"
                                type="text"
                                name="postcode_b"
                                value="<?= h($postcode_b) ?>"
                                maxlength="4"
                                required
                            >
                        </div>
                    </div>

                    <div class="confirmGroup">
                        <label class="confirmLabel" for="address">住所</label>
                        <input class="confirmText memberInput postcodeInput"
                            type="text"
                            id="address"
                            name="address"
                            value="<?= h($customer['address']) ?>"
                            required
                        >
                    </div>

                    <div class="confirmGroup">
                        <label class="confirmLabel" for="email">メールアドレス</label>
                        <input class="confirmText memberInput postcodeInput"
                            type="email"
                            id="email"
                            name="email"
                            value="<?= h($customer['email']) ?>"
                            required
                        >
                    </div>

                    <div class="confirmGroup">
                        <label class="confirmLabel" for="password">新しいパスワード</label>
                        <input class="confirmText memberInput postcodeInput"
                            type="password"
                            id="password"
                            name="password"
                            placeholder="変更する場合のみ入力"
                        >
                    </div>

                    <div class="confirmGroup">
                        <label class="confirmLabel" for="password_confirm">新しいパスワード確認用</label>
                        <input class="confirmText memberInput postcodeInput"
                            type="password"
                            id="password_confirm"
                            name="password_confirm"
                            placeholder="変更する場合のみ入力"
                        >
                    </div>
                </div>

                <div class="submitBtnWrap">
                    <button class="memberActionBtn" type="submit">変更する</button>
                </div>
            </form>

            <div class="completeLinkWrap">
                <p><a href="mypage.php" class="completeLink">マイページへ戻る</a></p>
            </div>
        </div>
    </section>
</main>

<?php require 'footer.php'; ?>