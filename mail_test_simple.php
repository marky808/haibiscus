<?php
// シンプルなメールテスト用ファイル
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 日本語設定
mb_language("Japanese");
mb_internal_encoding("UTF-8");

// メール設定
$to = 'info@hibiscus.velvet.jp';
$subject = 'テストメール - ' . date('Y-m-d H:i:s');
$message = "これはテストメールです。\n\n送信時刻: " . date('Y年m月d日 H時i分s秒');

// ヘッダー設定
$headers = "From: noreply@hibiscus.velvet.jp\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// メール送信
$result = mail($to, $subject, $message, $headers);

echo "<!DOCTYPE html>";
echo "<html lang='ja'>";
echo "<head><meta charset='UTF-8'><title>メールテスト結果</title></head>";
echo "<body>";
echo "<h1>メールテスト結果</h1>";
echo "<p><strong>送信結果:</strong> " . ($result ? "成功" : "失敗") . "</p>";
echo "<p><strong>送信先:</strong> " . htmlspecialchars($to) . "</p>";
echo "<p><strong>件名:</strong> " . htmlspecialchars($subject) . "</p>";
echo "<p><strong>送信時刻:</strong> " . date('Y-m-d H:i:s') . "</p>";

if ($result) {
    echo "<p style='color: green;'>✅ メール送信に成功しました！</p>";
    echo "<p>info@hibiscus.velvet.jp の受信箱を確認してください。</p>";
} else {
    echo "<p style='color: red;'>❌ メール送信に失敗しました。</p>";
    echo "<p>サーバー設定またはメールアドレスに問題がある可能性があります。</p>";
}

echo "<hr>";
echo "<h2>サーバー情報</h2>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><strong>HTTP Host:</strong> " . $_SERVER['HTTP_HOST'] . "</p>";

echo "</body></html>";
?>
