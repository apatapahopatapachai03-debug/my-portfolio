<?php
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
function isLoggedIn() {
    return isset($_SESSION['customer']);
}
function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}
// GET、POST用のチェック関数（整数パラメータチェック）、呼び出し側でPOST配列かGET配列かを指定して使用
function getIntParam(array $source, string $key): ?int
{
    $value = $source[$key] ?? '';

    // ctype_digitは数字で構成されている文字列かどうかをチェックする関数でreturnはboolean。
    // 数値は文字列にキャストしてからチェックすること。
    if (!ctype_digit((string)$value)) {
        return null;
    }

    return (int)$value;
}


// 画像表示用の関数
function getProductImage(array $product): string
{
    if (($product['status'] ?? '') === 'coming_soon') {
        return 'images/ComingSoon.png';
    }
    if (!empty($product['image_path'])) {
        return $product['image_path'];
    }
    return 'images/NoImage.png';
}

?>