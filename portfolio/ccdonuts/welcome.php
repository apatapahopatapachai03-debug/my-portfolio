<!-- ウエルカムメッセージバー -->
<?php
$user_name = $_SESSION['customer']['name'] ?? 'ゲスト';
?>

<div class="welcomeMessageArea addBorder">
    <p>
        ようこそ　
        <?= htmlspecialchars($user_name, ENT_QUOTES, 'UTF-8') ?>様
    </p>
</div>