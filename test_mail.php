<?php
// メール送信テスト用ページ
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 日本語文字化け対策
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// テスト用のメール送信
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $test_to = 'madoka.hibiscus1107@gmail.com';
    $test_subject = mb_encode_mimeheader('メール送信テスト - ULU美ボディアカデミー', 'UTF-8');
    $test_body = "これはメール送信テストです。\n\n";
    $test_body .= "送信日時: " . date('Y年m月d日 H:i:s') . "\n";
    $test_body .= "送信元IP: " . $_SERVER['REMOTE_ADDR'] . "\n";
    
    // ドメイン名を取得（ロリポップサーバーのドメインを使用）
    $domain = $_SERVER['HTTP_HOST'] ?? 'velvet-jp.main.jp';
    $from_email = 'test@' . $domain;
    
    $test_headers = "From: ULU美ボディアカデミー テスト <" . $from_email . ">\r\n";
    $test_headers .= "Reply-To: " . $from_email . "\r\n";
    $test_headers .= "Return-Path: " . $from_email . "\r\n";
    $test_headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
    $test_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $test_headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $test_headers .= "X-Sender-IP: " . $_SERVER['REMOTE_ADDR'] . "\r\n";
    
    $result = mail($test_to, $test_subject, $test_body, $test_headers);
    
    // デバッグ情報をログに記録
    $debug_info = "TEST Mail sent: " . ($result ? "SUCCESS" : "FAILED") . "\n";
    $debug_info .= "To: " . $test_to . "\n";
    $debug_info .= "Subject: " . $test_subject . "\n";
    $debug_info .= "Headers: " . $test_headers . "\n";
    $debug_info .= "Time: " . date('Y-m-d H:i:s') . "\n\n";
    file_put_contents('mail_debug.log', $debug_info, FILE_APPEND | LOCK_EX);
    
    if ($result) {
        $message = "テストメールの送信に成功しました！";
        $status = "success";
    } else {
        $message = "テストメールの送信に失敗しました。";
        $status = "error";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メール送信テスト</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .message {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
            text-align: center;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .test-button {
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            display: block;
            margin: 20px auto;
        }
        .test-button:hover {
            background-color: #0056b3;
        }
        .info {
            background-color: #e7f3ff;
            border: 1px solid #b3d7ff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .back-link {
            text-align: center;
            margin-top: 30px;
        }
        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>メール送信テスト</h1>
        
        <?php if (isset($message)): ?>
            <div class="message <?php echo $status; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <div class="info">
            <strong>テスト内容：</strong><br>
            • 送信先：madoka.hibiscus1107@gmail.com<br>
            • 件名：メール送信テスト - ULU美ボディアカデミー<br>
            • 本文：テスト用のメッセージ<br>
            • ログファイル：mail_debug.log に詳細を記録
        </div>
        
        <form method="post">
            <button type="submit" class="test-button">テストメール送信</button>
        </form>
        
        <div class="back-link">
            <a href="index.html">← メインページに戻る</a>
        </div>
    </div>
</body>
</html>
