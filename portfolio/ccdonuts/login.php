<?php $noindex = true; ?>
<?php require 'header.php'; ?>
<nav class="breadCrumb" aria-label="パンくずリスト">
  <ol>
    <li><a href="index.php">TOP</a></li>
    <li aria-current="page">＞ログイン</li>
  </ol>
</nav>
<main>
<?php require 'welcome.php'; ?>

    <section class="loginSection">
        <div class="innerWrap pageSidePadding pageTopWide">
            <h2 class="sectionTitle"><span>ログイン</span></h2>

            <div class="loginBox">
                <form class="loginForm" action="loginComplete.php" method="post">
                    <div class="loginFormGroup">
                        <label class="formLabel" for="loginMail">メールアドレス</label>
                        <input class="loginInput" type="email" id="loginMail" name="email" required>
                    </div>

                    <div class="loginFormGroup">
                        <label class="formLabel" for="loginPassword">パスワード</label>
                        <input class="loginInput" type="password" id="loginPassword" name="password" required>
                    </div>

                    <div class="submitBtnWrap loginBtnWrap">
                        <button class="memberActionBtn" type="submit">ログインする</button>
                    </div>
                </form>
            </div>

            <p class="loginRegisterLinkWrap">
            <a href="registerInput.php" class="loginRegisterLink">会員登録がまだの方はこちら</a>
            </p>
        </div>
        </section>
</main>
<?php require 'footer.php'; ?>