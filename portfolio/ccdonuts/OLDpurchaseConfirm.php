<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li><a href="login.php">＞ログイン</a></li>
    <li><a href="registerInput.php">＞会員登録</a></li>
    <li aria-current="page">＞入力確認</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
       <section class="purchaseSection">
        <div class="innerWrap pageTopWide">

            <h2 class="sectionTitle"><span>ご購入確認</span></h2>

            <div class="purchaseConfirmWrap">

                <!-- ご購入商品 -->
                <div class="purchaseBlock">
                    <h3 class="purchaseBlockTitle">ご購入商品</h3>

                    <div class="purchaseItem">
                        <div class="purchaseRow">
                            <p class="purchaseLabel">商品名</p>
                            <p class="purchaseValue textStrong">CCドーナツ 当店オリジナル（5個入り）</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">数量</p>
                            <p class="purchaseValue textStrong">1個</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">金額</p>
                            <p class="purchaseValue textStrong">税込 ￥1,500</p>
                        </div>
                    </div>

                    <div class="purchaseItem">
                        <div class="purchaseRow">
                            <p class="purchaseLabel">商品名</p>
                            <p class="purchaseValue textStrong">フルーツドーナツセット（12個入り）</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">数量</p>
                            <p class="purchaseValue textStrong">1個</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">金額</p>
                            <p class="purchaseValue textStrong">税込 ￥3,500</p>
                        </div>
                    </div>

                    <!-- 合計 -->
                    <div class="purcharseTotalWrap">
                        <div class="purchaseTotalRow">
                            <p class="purchaseLabel">合計数量</p>
                            <p class="purchaseValue textStrong">2個</p>
                        </div>
                        <div class="purchaseTotalRow">
                            <p class="purchaseLabel">合計金額</p>
                            <p class="purchaseValue textStrong">税込 ￥5,000</p>
                        </div>
                    </div>
                </div>

                <!-- お届け先 -->
                <div class="purchaseBlock">
                    <h3 class="purchaseBlockTitle">お届け先</h3>

                    <div class="purcharseTotalWrap">
                        <div class="purchaseRow">
                            <p class="purchaseLabel">お名前</p>
                            <p class="purchaseValue textStrong">ドーナツ太郎</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">郵便番号</p>
                            <p class="purchaseValue textStrong">123-4567</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">住所</p>
                            <p class="purchaseValue textStrong">千葉県〇〇市中央1-1-1</p>
                        </div>
                    </div>
                </div>

                <!-- 支払い方法 -->
                <div class="purchaseBlock">
                    <h3 class="purchaseBlockTitle">お支払い方法</h3>
                </div>
            </div>


                <div class="cardRegistrationGuide">
                    <!-- 支払い方法の中身 -->
                    <div class="purchaseBlock">
                        <div class="purchaseRow">
                            <p class="purchaseLabel">お支払い</p>
                            <p class="purchaseValue textStrong">クレジットカード</p>
                        </div>
                        <div class="purchaseRow">
                            <p class="purchaseLabel">ブランド</p>
                            <p class="purchaseValue textStrong">JCB</p>
                        </div>
                    </div>
                </div>
                    <!-- ボタン -->
                    <div class="submitBtnWrap">
                        <button class="memberActionBtn">購入を確定する</button>
                    </div>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>