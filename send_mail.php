<?php
// エラーレポートを有効にする（開発時のみ）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// CORS対応（必要に応じて）
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// POSTリクエストのみ受け付ける
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

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

// 送信先メールアドレス
$to = 'madoka.hibiscus1107@gmail.com';

// 件名
$subject = 'ULU美ボディアカデミー 無料相談お申込み';

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

// メールヘッダー
$headers = array(
    'From' => $email,
    'Reply-To' => $email,
    'Return-Path' => $email,
    'X-Mailer' => 'PHP/' . phpversion(),
    'Content-Type' => 'text/plain; charset=UTF-8'
);

// ヘッダーを文字列に変換
$header_string = '';
foreach ($headers as $key => $value) {
    $header_string .= $key . ': ' . $value . "\r\n";
}

// メール送信
$mail_sent = mb_send_mail($to, $subject, $body, $header_string);

if ($mail_sent) {
    // 成功時の処理
    echo json_encode([
        'status' => 'success', 
        'message' => 'お問い合わせありがとうございます。24時間以内にご返信いたします。'
    ]);
    
    // 自動返信メールを送信（オプション）
    $auto_reply_subject = 'ULU美ボディアカデミー - お問い合わせありがとうございます';
    $auto_reply_body = $name . " 様\n\n";
    $auto_reply_body .= "この度は、ULU美ボディアカデミーにお問い合わせいただき、誠にありがとうございます。\n\n";
    $auto_reply_body .= "お送りいただいたお問い合わせ内容を確認いたしました。\n";
    $auto_reply_body .= "24時間以内に担当者よりご返信させていただきますので、少々お待ちください。\n\n";
    $auto_reply_body .= "なお、このメールは自動送信メールです。\n";
    $auto_reply_body .= "ご返信いただいても回答できませんので、ご了承ください。\n\n";
    $auto_reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    $auto_reply_body .= "ULU美ボディアカデミー\n";
    $auto_reply_body .= "運営：Hi美scus\n";
    $auto_reply_body .= "〒213-0013\n";
    $auto_reply_body .= "神奈川県川崎市高津区末長1-47-1　梶が谷プラザビル114\n";
    $auto_reply_body .= "TEL：044-888-8688\n";
    $auto_reply_body .= "━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $auto_reply_headers = array(
        'From' => 'madoka.hibiscus1107@gmail.com',
        'Reply-To' => 'madoka.hibiscus1107@gmail.com',
        'Content-Type' => 'text/plain; charset=UTF-8'
    );
    
    $auto_reply_header_string = '';
    foreach ($auto_reply_headers as $key => $value) {
        $auto_reply_header_string .= $key . ': ' . $value . "\r\n";
    }
    
    mb_send_mail($email, $auto_reply_subject, $auto_reply_body, $auto_reply_header_string);
    
} else {
    // 失敗時の処理
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'メール送信に失敗しました。お手数ですが、直接お電話（044-888-8688）でお問い合わせください。'
    ]);
}
?>
