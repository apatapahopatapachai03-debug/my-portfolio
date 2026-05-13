<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../mypage.php');
    exit;
}

$customer_id = $_SESSION['customer']['id'];

$name = trim($_POST['name'] ?? '');
$furigana = trim($_POST['furigana'] ?? '');
$postcode_a = trim($_POST['postcode_a'] ?? '');
$postcode_b = trim($_POST['postcode_b'] ?? '');
$address = trim($_POST['address'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');
$password_confirm = trim($_POST['password_confirm'] ?? '');

$errors = [];

// 未入力チェック
if ($name === '') {
    $errors[] = 'お名前を入力してください。';
}

if ($furigana === '') {
    $errors[] = 'お名前（フリガナ）を入力してください。';
}

if ($postcode_a === '') {
    $errors[] = '郵便番号の前半3桁を入力してください。';
}

if ($postcode_b === '') {
    $errors[] = '郵便番号の後半4桁を入力してください。';
}

if ($address === '') {
    $errors[] = 'ご住所を入力してください。';
}

if ($email === '') {
    $errors[] = 'メールアドレスを入力してください。';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'メールアドレスの形式が正しくありません。';
}

// 文字数チェック
if ($name !== '' && mb_strlen($name) > 100) {
    $errors[] = 'お名前は100文字以内で入力してください。';
}

if ($furigana !== '' && mb_strlen($furigana) > 100) {
    $errors[] = 'お名前（フリガナ）は100文字以内で入力してください。';
}

if ($address !== '' && mb_strlen($address) > 200) {
    $errors[] = '住所は200文字以内で入力してください。';
}

// 形式チェック
if ($furigana !== '' && !preg_match('/^[ァ-ヶー　 ]+$/u', $furigana)) {
    $errors[] = 'お名前（フリガナ）は全角カタカナで入力してください。';
}

if ($postcode_a !== '' && !preg_match('/^\d{3}$/', $postcode_a)) {
    $errors[] = '郵便番号の前半3桁を正しく入力してください。';
}

if ($postcode_b !== '' && !preg_match('/^\d{4}$/', $postcode_b)) {
    $errors[] = '郵便番号の後半4桁を正しく入力してください。';
}

// パスワードは入力された場合だけ変更
if ($password !== '' || $password_confirm !== '') {
    if ($password === '') {
        $errors[] = '新しいパスワードを入力してください。';
    }

    if ($password_confirm === '') {
        $errors[] = '新しいパスワード確認用を入力してください。';
    }

    if ($password !== '' && (!preg_match('/^[A-Za-z0-9]+$/', $password) || strlen($password) < 8 || strlen($password) > 20)) {
        $errors[] = 'パスワードは半角英数字8文字以上20文字以内で入力してください。記号の使用はできません。';
    }

    if ($password !== '' && $password_confirm !== '' && $password !== $password_confirm) {
        $errors[] = 'パスワードとパスワード確認用が一致しません。';
    }
}

if (!empty($errors)) {
    $_SESSION['flash_message'] = implode("\n", $errors);
    header('Location: ../customerEdit.php');
    exit;
}

// バリデーション通過後、DB から現在の値を取得
$stmt = $pdo->prepare('SELECT * FROM customers WHERE id = ?');
$stmt->execute([$customer_id]);
$current = $stmt->fetch(PDO::FETCH_ASSOC);

// 郵便番号を結合した送信値
$postcode = $postcode_a . '-' . $postcode_b;

// 変更があるか確認
$isChanged =
    $current['name']     !== $name     ||
    $current['furigana'] !== $furigana ||
    $current['postcode'] !== $postcode ||
    $current['address']  !== $address  ||
    $current['email']    !== $email    ||
    $password !== ''; // パスワードは入力があれば必ず変更扱い

if (!$isChanged) {
    // 変更なし → フラッシュメッセージなしでリダイレクト
    header('Location: ../mypage.php');
    exit;
}

if ($password !== '') {
    // 本番想定ならこっち
    // $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 現在の保存方式に合わせる場合
    $password_hash = $password;

    $stmt = $pdo->prepare('
        UPDATE customers
        SET name = ?,
            furigana = ?,
            postcode = ?,
            address = ?,
            email = ?,
            password_hash = ?
        WHERE id = ?
    ');

    $stmt->execute([
        $name,
        $furigana,
        $postcode,
        $address,
        $email,
        $password_hash,
        $customer_id
    ]);
} else {
    $stmt = $pdo->prepare('
        UPDATE customers
        SET name = ?,
            furigana = ?,
            postcode = ?,
            address = ?,
            email = ?
        WHERE id = ?
    ');

    $stmt->execute([
        $name,
        $furigana,
        $postcode,
        $address,
        $email,
        $customer_id
    ]);
}

$_SESSION['flash_message'] = '会員情報を更新しました。';


header('Location: ../mypage.php');
exit;