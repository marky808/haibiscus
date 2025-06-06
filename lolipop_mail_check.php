<?php
// ロリポップサーバーのメール設定確認ツール
echo "<h2>ロリポップサーバー メール設定確認</h2>";

// 基本情報
echo "<h3>サーバー基本情報</h3>";
echo "Server Name: " . $_SERVER['SERVER_NAME'] . "<br>";
echo "HTTP Host: " . $_SERVER['HTTP_HOST'] . "<br>";
echo "PHP Version: " . phpversion() . "<br>";

// メール関数の確認
echo "<h3>メール機能確認</h3>";
echo "mail() function available: " . (function_exists('mail') ? '✓ 利用可能' : '✗ 利用不可') . "<br>";

// 推奨設定での簡単なテスト送信
echo "<h3>テスト送信実行</h3>";

$to = 'info@hibiscus.velvet.jp';
$subject = 'ロリポップメール設定テスト - ' . date('Y-m-d H:i:s');
$body = "ロリポップサーバーからのテストメールです。\n\n";
$body .= "送信時刻: " . date('Y-m-d H:i:s') . "\n";
$body .= "サーバー: " . $_SERVER['SERVER_NAME'] . "\n";
$body .= "送信者IP: " . $_SERVER['SERVER_ADDR'] . "\n";

// ロリポップ推奨のヘッダー設定
$headers = array();
$headers[] = 'From: noreply@hibiscus.velvet.jp';
$headers[] = 'Reply-To: noreply@hibiscus.velvet.jp';
$headers[] = 'X-Mailer: PHP/' . phpversion();
$headers[] = 'Content-Type: text/plain; charset=UTF-8';

$header_string = implode("\r\n", $headers);

echo "送信先: " . $to . "<br>";
echo "件名: " . $subject . "<br>";
echo "送信者: noreply@hibiscus.velvet.jp<br>";

// mail()の結果を詳細に確認
$mail_result = mail($to, $subject, $body, $header_string);

echo "<strong>mail()関数の戻り値: " . ($mail_result ? 'TRUE (送信成功)' : 'FALSE (送信失敗)') . "</strong><br>";

if ($mail_result) {
    echo "<p style='color: green;'>✓ mail()関数は成功を返しました。</p>";
    echo "<p><strong>重要:</strong> mail()が成功を返しても、実際の配信は保証されません。<br>";
    echo "以下を確認してください：</p>";
    echo "<ul>";
    echo "<li>ロリポップの管理画面で info@hibiscus.velvet.jp が作成されているか</li>";
    echo "<li>メールボックスの容量が十分か</li>";
    echo "<li>転送設定が正しく行われているか</li>";
    echo "<li>スパムフィルターに引っかかっていないか</li>";
    echo "</ul>";
} else {
    echo "<p style='color: red;'>✗ mail()関数が失敗しました。</p>";
    echo "<p>PHPのエラーログまたはサーバーのメールログを確認してください。</p>";
}

// エラー情報の取得試行
if (function_exists('error_get_last')) {
    $last_error = error_get_last();
    if ($last_error) {
        echo "<h3>最後のエラー情報</h3>";
        echo "<pre>" . print_r($last_error, true) . "</pre>";
    }
}

echo "<h3>推奨対応策</h3>";
echo "<p>1. <strong>ロリポップ管理画面確認</strong>: info@hibiscus.velvet.jp の作成状況を確認</p>";
echo "<p>2. <strong>SMTP認証使用</strong>: より確実な send_mail_smtp.php の使用を検討</p>";
echo "<p>3. <strong>別のメールアドレス</strong>: 既存の動作確認済みメールアドレスでのテスト</p>";

?>
