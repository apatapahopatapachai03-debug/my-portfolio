<?php $noindex = true; ?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['customer'])) {
    header('Location: /ccdonuts/mypage.php');
    exit;
}
?>

<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
      <li><a href="index.php">TOP</a></li>
      <li><a href="login.php">＞ログイン</a></li>
      <li aria-current="page">＞会員登録</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>
        
    <section class="memberRegisterSection">
        <div class="innerWrap pageTopWide">
            <h2 class="sectionTitle"><span>会員登録</span></h2>

            <form class="memberRegisterForm" action="registerConfirm.php" method="post">
                <div class="formGroup">
                    <label class="formLabel" for="userName">
                    お名前 <span class="required">（必須）</span>
                    </label>
                    <input class="formInput" type="text" id="userName" name="name" placeholder="ドーナツ太郎" required maxlength="100">
                </div>

                <div class="formGroup">
                    <label class="formLabel" for="userKana">
                    お名前（フリガナ） <span class="required">（必須）</span>
                    </label>
                    <input class="formInput" type="text" id="userKana" name="furigana" placeholder="ドーナツタロウ" required maxlength="100" pattern="^[ァ-ヶー　 ]+$">
                </div>

                <div class="formGroup">
                    <p class="formLabel">
                    郵便番号 <span class="required">（必須）</span>
                    </p>
                    <div class="postcodeWrap">
                    <input class="formInput postcodeInput postcodeInputFirst" type="text" name="postcode_a" placeholder="123" required pattern="\d{3}" maxlength="3" inputmode="numeric">
                    <input class="formInput postcodeInput postcodeInputLast" type="text" name="postcode_b" placeholder="4567" required pattern="\d{4}" maxlength="4" inputmode="numeric">
                    </div>
                </div>

                <div class="formGroup">
                    <label class="formLabel" for="address">
                    住所 <span class="required">（必須）</span>
                    </label>
                    <input class="formInput" type="text" id="address" name="address" placeholder="千葉県〇〇市中央1-1-1" required maxlength="200">
                </div>

                <div class="formGroup">
                    <label class="formLabel" for="email">
                    メールアドレス <span class="required">（必須）</span>
                    </label>
                    <input class="formInput" type="email" id="email" name="email" placeholder="123@gmail.com" required>
                </div>

                <div class="formGroup">
                    <label class="formLabel" for="emailConfirm">
                    メールアドレス確認用 <span class="required">（必須）</span>
                    </label>
                    <input class="formInput" type="email" id="emailConfirm" name="email_confirm" placeholder="123@gmail.com" required>
                </div>

                <div class="formGroup">
                    <label class="formLabel" for="password">
                    パスワード <span class="required">（必須）</span>
                    </label>
                    <p class="formNote">半角英数字8文字以上20文字以内で入力してください。※記号の使用はできません</p>
                    <input class="formInput" type="password" id="password" name="password" placeholder="123456abcd" required minlength="8" maxlength="20" pattern="^[A-Za-z0-9]+$">
                </div>

                <div class="formGroup">
                    <label class="formLabel" for="passwordConfirm">
                    パスワード確認用 <span class="required">（必須）</span>
                    </label>
                    <input class="formInput" type="password" id="passwordConfirm" name="password_confirm" placeholder="123456abcd" required minlength="8" maxlength="20" pattern="^[A-Za-z0-9]+$">
                </div>

                <div class="submitBtnWrap">
                    <button class="memberActionBtn" type="submit">入力確認する</button>
                </div>
            </form>
        </div>
    </section>
</main>
<?php require 'footer.php'; ?>