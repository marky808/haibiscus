<?php
// PHPMailer を使用したメール送信（Gmail認証対応版）
// 使用前に PHPMailer をダウンロードする必要があります

// エラーレポートを有効にする（開発時のみ）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS対応
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// 日本語文字化け対策
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// POSTリクエストのみ受け付ける
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// PHPMailer のファイルが存在するかチェック
$phpmailer_files = [
    'PHPMailer/src/PHPMailer.php',
    'PHPMailer/src/SMTP.php',
    'PHPMailer/src/Exception.php'
];

$phpmailer_available = true;
foreach ($phpmailer_files as $file) {
    if (!file_exists($file)) {
        $phpmailer_available = false;
        break;
    }
}

if (!$phpmailer_available) {
    // PHPMailer が利用できない場合は通常のmail()関数を使用
    include 'send_mail.php';
    exit;
}

// PHPMailer を読み込み
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// フォームデータを取得
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$age = isset($_POST['age']) ? trim($_POST['age']) : '';
$plan = isset($_POST['plan']) ? trim($_POST['plan']) : '';
$goals = isset($_POST['goals']) ? trim($_POST['goals']) : '';
$experience = isset($_POST['experience']) ? trim($_POST['experience']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// 必須項目のバリデーション
if (empty($name) || empty($email)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'お名前とメールアドレスは必須です。']);
    exit;
}

// メールアドレスの形式チェック
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'メールアドレスの形式が正しくありません。']);
    exit;
}

try {
    // PHPMailer インスタンスを作成
    $mail = new PHPMailer(true);

    // SMTP設定（ロリポップサーバー用）
    $mail->isSMTP();
    $mail->Host = 'smtp.lolipop.jp';
    $mail->SMTPAuth = true;
    $mail->Username = 'velvet-jp-hibiscus'; // ロリポップのFTPユーザー名
    $mail->Password = 'your_password_here'; // ← パスワードを設定してください
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // 文字エンコーディング設定
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    // 送信者設定
    $domain = $_SERVER['HTTP_HOST'] ?? 'velvet-jp.main.jp';
    $mail->setFrom('noreply@' . $domain, 'ULU美ボディアカデミー');
    $mail->addReplyTo($email, $name);

    // 受信者設定
    $mail->addAddress('madoka.hibiscus1107@gmail.com', 'ULU美ボディアカデミー');

    // メール内容設定
    $mail->isHTML(false);
    $mail->Subject = 'ULU美ボディアカデミー 無料相談お申込み';

    // メール本文を作成
    $body = "ULU美ボディアカデミーの無料相談にお申込みがありました。\n\n";
    $body .= "【お名前】\n" . $name . "\n\n";
    $body .= "【メールアドレス】\n" . $email . "\n\n";
    $body .= "【電話番号】\n" . ($phone ? $phone : '未入力') . "\n\n";
    $body .= "【年齢】\n" . ($age ? $age : '未選択') . "\n\n";
    $body .= "【ご希望のプラン】\n" . ($plan ? $plan : '未選択') . "\n\n";
    $body .= "【ボディメイクの目標・お悩み】\n" . ($goals ? $goals : '未入力') . "\n\n";
    $body .= "【過去のダイエット経験】\n" . ($experience ? $experience : '未入力') . "\n\n";
    $body .= "【その他ご質問・ご要望】\n" . ($message ? $message : '未入力') . "\n\n";
    $body .= "---\n";
    $body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";
    $body .= "送信元IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

    $mail->Body = $body;

    // メール送信実行
    $mail->send();

    // 成功レスポンス
    echo json_encode([
        'status' => 'success', 
        'message' => 'お問い合わせありがとうございます。24時間以内にご返信いたします。'
    ]);

} catch (Exception $e) {
    // エラーログに記録
    $error_log = "PHPMailer Error: " . $mail->ErrorInfo . "\n";
    $error_log .= "Exception: " . $e->getMessage() . "\n";
    $error_log .= "Time: " . date('Y-m-d H:i:s') . "\n\n";
    file_put_contents('mail_error.log', $error_log, FILE_APPEND | LOCK_EX);

    // エラーレスポンス
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'メール送信に失敗しました。お手数ですが、直接お電話（044-888-8688）でお問い合わせください。'
    ]);
}
?>
