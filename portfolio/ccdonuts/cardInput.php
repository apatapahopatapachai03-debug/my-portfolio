<?php
require_once __DIR__ . '/app/auth.php';
require_once __DIR__ . '/app/functions.php';
require_once __DIR__ . '/app/db.php';

// 決済関連ページの検索除け用->noindex header.phpのheadタグで判定
$noindex = true;

$old = $_SESSION['old'] ?? [];
$flashError = $_SESSION['flash_error'] ?? '';
$oldBrand = $old['card_brand'] ?? 'JCB';

require 'header.php';
?>

<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="cart.php">＞カート</a></li>
    <li><a href="purchaseConfirm.php">＞購入確認</a></li>
    <li aria-current="page">＞カード情報登録</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>  
    <section class="cardRegisterSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>カード情報登録</span></h2>

            <!-- カード登録防止メッセージ -->
            <p class="blink" style="color: red; font-weight: bold; font-size: 16px; margin: 10px auto;">※演習用サイトの為、実際のカード情報を絶対に登録しないでください。</p>
            <style>
                .blink {
             animation: blinkCycle 5s infinite; /* 全体の長さ：7秒（点滅2秒＋休止5秒） */
            }
            /* 点滅アニメーション */
            @keyframes blinkCycle {
                0%   { opacity: 1; }
                4%   { opacity: 0; }
                6%  { opacity: 1; }
                8%  { opacity: 0; }
                10%  { opacity: 1; }
                100% { opacity: 1; }
            }
            </style>
            <form class="cardRegisterForm" action="cardConfirm.php" method="post">
                <div class="cardFormGroup firstFormGroup">
                    <label class="textBase" for="cardName">
                    お名前 <span class="required">（必須）</span>
                    </label>
                    <input class="cardInput cardInputName" type="text" id="cardName" name="card_name" value="<?= h($old['card_name'] ?? '') ?>" placeholder="ドーナツ太郎" required maxlength="100">
                </div>

                <div class="cardFormGroup">
                    <label class="textBase" for="cardNumber">
                    カード番号 <span class="required">（必須）</span>
                    </label>
                    <input class="cardInput" type="text" id="cardNumber" name="card_number" value="<?= h($old['card_number'] ?? '') ?>" placeholder="半角数字で入力して下さい。: 1234567890123456 " required maxlength="16">
                </div>

                <div class="cardFormGroup">
                    <p class="textBase">
                    カード会社 <span class="required">（必須）</span>
                    </p>
                    <div class="cardBrandWrap">
                        <label class="cardBrandItem">
                            <input type="radio" name="card_brand" value="JCB" <?= $oldBrand === 'JCB' ? 'checked' : '' ?> required>
                            <span class="textBase cardBrandText">JCB</span>
                        </label>

                        <label class="cardBrandItem">
                            <input type="radio" name="card_brand" value="Visa" <?= $oldBrand === 'Visa' ? 'checked' : '' ?>>
                            <span class="textBase cardBrandText">Visa</span>
                        </label>

                        <label class="cardBrandItem">
                            <input type="radio" name="card_brand" value="Mastercard" <?= $oldBrand === 'Mastercard' ? 'checked' : '' ?>>
                            <span class="textBase cardBrandText">Mastercard</span>
                        </label>
                    </div>
                </div>

                <div class="cardFormGroup">
                    <p class="textBase">
                    有効期限 <span class="required">（必須）</span>
                    </p>

                    <div class="expiryWrap">
                    <div class="expiryRow">
                        <input class="cardInput expiryInput" type="text" name="expire_month" value="<?= h($old['expire_month'] ?? '') ?>" placeholder="4" required maxlength="2">
                        <span class="textBase expiryUnit">月</span>
                    </div>

                    <div class="expiryRow">
                        <input class="cardInput expiryInput" type="text" name="expire_year" value="<?= h($old['expire_year'] ?? '') ?>" placeholder="25" required maxlength="2">
                        <span class="textBase expiryUnit">年</span>
                    </div>
                    </div>
                </div>

                <div class="cardFormGroup">
                    <label class="textBase" for="securityCode">
                    セキュリティコード <span class="required">（必須）</span>
                    </label>
                    <input class="cardInput" type="text" id="securityCode" name="security_code" value="<?= h($old['security_code'] ?? '') ?>" placeholder="半角数字3桁: 123" required maxlength="3">
                </div>

                <div class="submitBtnWrap">
                    <button class="memberActionBtn" type="submit">入力確認へ進む</button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php
unset($_SESSION['old'], $_SESSION['flash_error']);
require 'footer.php';
?>
