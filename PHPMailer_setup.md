# PHPMailer セットアップ手順

Gmail認証問題を解決するため、PHPMailerライブラリを使用することをお勧めします。

## ダウンロード手順

### 方法1: GitHubから直接ダウンロード

1. https://github.com/PHPMailer/PHPMailer にアクセス
2. 「Code」→「Download ZIP」をクリック
3. ダウンロードしたZIPファイルを解凍
4. `PHPMailer-master` フォルダ内の `src` フォルダを `PHPMailer` にリネーム
5. このフォルダをウェブサイトのルートディレクトリにアップロード

### 方法2: Composerを使用（推奨）

サーバーにComposerがインストールされている場合：

```bash
composer require phpmailer/phpmailer
```

## ファイル構成

アップロード後のファイル構成：
```
/
├── index.html
├── send_mail.php (修正版)
├── send_mail_smtp.php (PHPMailer版)
├── PHPMailer/
│   ├── src/
│   │   ├── PHPMailer.php
│   │   ├── SMTP.php
│   │   └── Exception.php
│   └── ...
└── その他のファイル
```

## 設定手順

1. `send_mail_smtp.php` の以下の行を編集：
   ```php
   $mail->Username = 'velvet-jp-hibiscus'; // ロリポップのFTPユーザー名
   $mail->Password = 'your_password_here'; // ← パスワードを設定してください
   ```

2. ロリポップのFTPパスワードを設定

3. `index.html` のフォームアクション先を変更：
   ```html
   <form class="contact-form" action="send_mail_smtp.php" method="POST">
   ```

## メール認証設定

ロリポップでSMTP認証を有効にする：

1. ロリポップのユーザー専用ページにログイン
2. 「メール」→「メール設定」
3. 「SMTP認証」を有効にする

## テスト方法

1. PHPMailerをアップロード後、`test_mail.php` でテスト送信
2. 問題があれば `mail_error.log` を確認
3. 成功すれば本番のコンタクトフォームで試用

## 注意事項

- パスワードはセキュリティのため環境変数や設定ファイルで管理することを推奨
- PHPMailerを使用することで、Gmail認証要件をクリアできます
- 自動返信機能も正常に動作するようになります
