<?php
// お問い合わせ管理画面
// inquiries.csv とログファイルの内容を表示

error_reporting(E_ALL);
ini_set('display_errors', 1);

// 簡易認証（パスワード保護）
session_start();
$password = 'hibiscus2025'; // ← パスワードを変更してください

if (isset($_POST['password'])) {
    if ($_POST['password'] === $password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'パスワードが正しくありません。';
    }
}

if (isset($_POST['logout'])) {
    unset($_SESSION['admin_logged_in']);
}

$logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'];
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ管理 - ULU美ボディアカデミー</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 20px auto;
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
            margin-bottom: 30px;
        }
        .login-form {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .error {
            color: #dc3545;
            text-align: center;
            margin: 10px 0;
        }
        .header-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .inquiry-item {
            background: #f8f9fa;
            margin: 15px 0;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .inquiry-header {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .inquiry-details {
            line-height: 1.6;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            margin: 40px 0;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            border-left: 4px solid #28a745;
        }
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #28a745;
        }
        .stat-label {
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <?php if (!$logged_in): ?>
        <div class="login-form">
            <h2 style="text-align: center; margin-bottom: 20px;">管理画面ログイン</h2>
            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="post">
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <input type="submit" value="ログイン">
            </form>
        </div>
    <?php else: ?>
        <div class="container">
            <div class="header-bar">
                <h1 style="margin: 0;">お問い合わせ管理</h1>
                <form method="post" style="margin: 0;">
                    <input type="submit" name="logout" value="ログアウト" style="width: auto; padding: 8px 16px;">
                </form>
            </div>

            <?php
            $csv_file = 'inquiries.csv';
            $log_file = 'inquiries.log';
            
            // 統計情報
            $total_inquiries = 0;
            $today_inquiries = 0;
            $today = date('Y-m-d');
            
            if (file_exists($csv_file)) {
                $csv_data = file($csv_file);
                $total_inquiries = count($csv_data) - 1; // ヘッダー行を除く
                
                foreach ($csv_data as $line) {
                    if (strpos($line, $today) !== false) {
                        $today_inquiries++;
                    }
                }
            }
            ?>

            <div class="stats">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $total_inquiries; ?></div>
                    <div class="stat-label">総お問い合わせ数</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $today_inquiries; ?></div>
                    <div class="stat-label">今日のお問い合わせ</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo date('Y年m月d日'); ?></div>
                    <div class="stat-label">最終更新</div>
                </div>
            </div>

            <h2>お問い合わせ一覧（CSV形式）</h2>
            <?php if (file_exists($csv_file)): ?>
                <table>
                    <?php
                    $csv_data = file($csv_file);
                    foreach ($csv_data as $index => $line) {
                        $data = str_getcsv($line);
                        if ($index === 0) {
                            echo "<thead><tr>";
                            foreach ($data as $header) {
                                echo "<th>" . htmlspecialchars($header) . "</th>";
                            }
                            echo "</tr></thead><tbody>";
                        } else {
                            echo "<tr>";
                            foreach ($data as $cell) {
                                echo "<td>" . htmlspecialchars($cell) . "</td>";
                            }
                            echo "</tr>";
                        }
                    }
                    echo "</tbody>";
                    ?>
                </table>
            <?php else: ?>
                <div class="no-data">CSVファイルが見つかりません。</div>
            <?php endif; ?>

            <h2>詳細ログ</h2>
            <?php if (file_exists($log_file)): ?>
                <div style="background: #f4f4f4; padding: 15px; border-radius: 5px; white-space: pre-wrap; max-height: 500px; overflow-y: auto;">
<?php echo htmlspecialchars(file_get_contents($log_file)); ?>
                </div>
            <?php else: ?>
                <div class="no-data">ログファイルが見つかりません。</div>
            <?php endif; ?>

            <div style="margin-top: 40px; text-align: center; color: #666;">
                <p>※ CSVファイルをダウンロードして Excel で開くことができます</p>
                <p>ファイル場所: <code><?php echo $csv_file; ?></code></p>
            </div>
        </div>
    <?php endif; ?>
</body>
</html>
