<?php
// サーバー情報確認ページ
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>サーバー情報確認</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
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
        .info-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .info-section h3 {
            margin-top: 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
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
        <h1>サーバー情報確認</h1>
        
        <div class="info-section">
            <h3>基本サーバー情報</h3>
            <table>
                <tr><th>項目</th><th>値</th></tr>
                <tr><td>サーバーソフトウェア</td><td><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'N/A'; ?></td></tr>
                <tr><td>PHPバージョン</td><td><?php echo phpversion(); ?></td></tr>
                <tr><td>ホスト名</td><td><?php echo $_SERVER['HTTP_HOST'] ?? 'N/A'; ?></td></tr>
                <tr><td>ドキュメントルート</td><td><?php echo $_SERVER['DOCUMENT_ROOT'] ?? 'N/A'; ?></td></tr>
                <tr><td>スクリプト名</td><td><?php echo $_SERVER['SCRIPT_NAME'] ?? 'N/A'; ?></td></tr>
            </table>
        </div>
        
        <div class="info-section">
            <h3>メール関連設定</h3>
            <table>
                <tr><th>設定項目</th><th>値</th></tr>
                <tr><td>mail() 関数</td><td><?php echo function_exists('mail') ? '利用可能' : '利用不可'; ?></td></tr>
                <tr><td>mb_send_mail() 関数</td><td><?php echo function_exists('mb_send_mail') ? '利用可能' : '利用不可'; ?></td></tr>
                <tr><td>sendmail_path</td><td><?php echo ini_get('sendmail_path') ?: '設定なし'; ?></td></tr>
                <tr><td>SMTP</td><td><?php echo ini_get('SMTP') ?: '設定なし'; ?></td></tr>
                <tr><td>smtp_port</td><td><?php echo ini_get('smtp_port') ?: '設定なし'; ?></td></tr>
            </table>
        </div>
        
        <div class="info-section">
            <h3>文字エンコーディング設定</h3>
            <table>
                <tr><th>設定項目</th><th>値</th></tr>
                <tr><td>default_charset</td><td><?php echo ini_get('default_charset') ?: '設定なし'; ?></td></tr>
                <tr><td>mbstring.language</td><td><?php echo ini_get('mbstring.language') ?: '設定なし'; ?></td></tr>
                <tr><td>mbstring.internal_encoding</td><td><?php echo ini_get('mbstring.internal_encoding') ?: '設定なし'; ?></td></tr>
                <tr><td>mbstring.http_output</td><td><?php echo ini_get('mbstring.http_output') ?: '設定なし'; ?></td></tr>
            </table>
        </div>
        
        <div class="info-section">
            <h3>ログファイル確認</h3>
            <?php
            $log_file = 'mail_debug.log';
            if (file_exists($log_file)) {
                echo "<p><strong>mail_debug.log の内容:</strong></p>";
                echo "<pre style='background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto;'>";
                echo htmlspecialchars(file_get_contents($log_file));
                echo "</pre>";
            } else {
                echo "<p>mail_debug.log ファイルはまだ作成されていません。</p>";
            }
            ?>
        </div>
        
        <div class="back-link">
            <a href="test_mail.php">← メールテストページ</a> | 
            <a href="index.html">メインページ</a>
        </div>
    </div>
</body>
</html>
