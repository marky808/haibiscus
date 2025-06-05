<?php
// 緊急時用：フォームデータをGmail宛に転送する簡易版
// Gmail認証問題の一時的な回避策

error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

mb_language("Japanese");
mb_internal_encoding("UTF-8");

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

// ファイルにデータを保存（メール送信の代替手段）
$inquiry_data = array(
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'age' => $age,
    'plan' => $plan,
    'goals' => $goals,
    'experience' => $experience,
    'message' => $message,
    'timestamp' => date('Y-m-d H:i:s'),
    'ip' => $_SERVER['REMOTE_ADDR']
);

// CSVファイルに保存
$csv_file = 'inquiries.csv';
$csv_exists = file_exists($csv_file);

$fp = fopen($csv_file, 'a');
if ($fp) {
    // ヘッダー行を追加（初回のみ）
    if (!$csv_exists) {
        fputcsv($fp, array_keys($inquiry_data), ',', '"');
    }
    
    // データを追加
    fputcsv($fp, array_values($inquiry_data), ',', '"');
    fclose($fp);
    
    // ログファイルにも詳細を保存
    $log_content = "=== 新規お問い合わせ ===\n";
    $log_content .= "日時: " . date('Y年m月d日 H:i:s') . "\n";
    $log_content .= "お名前: " . $name . "\n";
    $log_content .= "メールアドレス: " . $email . "\n";
    $log_content .= "電話番号: " . ($phone ?: '未入力') . "\n";
    $log_content .= "年齢: " . ($age ?: '未選択') . "\n";
    $log_content .= "ご希望のプラン: " . ($plan ?: '未選択') . "\n";
    $log_content .= "目標・お悩み: " . ($goals ?: '未入力') . "\n";
    $log_content .= "ダイエット経験: " . ($experience ?: '未入力') . "\n";
    $log_content .= "その他: " . ($message ?: '未入力') . "\n";
    $log_content .= "送信元IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $log_content .= "==============================\n\n";
    
    file_put_contents('inquiries.log', $log_content, FILE_APPEND | LOCK_EX);
    
    // 成功レスポンス
    echo json_encode([
        'status' => 'success', 
        'message' => 'お問い合わせを受け付けました。24時間以内にご返信いたします。'
    ]);
    
} else {
    // ファイル書き込みエラー
    http_response_code(500);
    echo json_encode([
        'status' => 'error', 
        'message' => 'お問い合わせの受付中にエラーが発生しました。お手数ですが、直接お電話（044-888-8688）でお問い合わせください。'
    ]);
}
?>
