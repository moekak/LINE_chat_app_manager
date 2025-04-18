# LINE連携チャットシステム(管理画面)
## 概要
集客活動で活用されるチャットアプリの開発。従来のLINEを使用した方法では、アカウントが凍結されるなどの課題があったため、独自のチャットシステムを構築。

## 機能
- 既読確認
- リアルタイム通知
- ユーザーブロック
- 初回メッセージ登録
- メッセージテンプレート作成
- ユーザー編集
- 管理者LINEアカウント編集
- LINE通知文言編集
- ステータス切り替え
- 一斉メッセージ送信

## 技術スタック

### バックエンド
- **フレームワーク**: Laravel v11.9
- **言語**: PHP v8.3.10
- **パッケージ管理**: Composer v2.8.5
- **データベース**: AWS RDS (MySQL/Aurora)
- **ウェブサーバー**: Apache

### フロントエンド
- **テンプレートエンジン**: Laravel Blade
- **言語**: JavaScript
- **スタイリング**: CSS
- **パッケージ管理**: NPM v10.8.2
- **ビルドツール**: Webpack

### クラウドインフラ
- **サーバーホスティング**: AWS EC2
- **ストレージ**: AWS S3
- **モニタリング**: AWS CloudWatch
- **ネットワーク**: AWS Route 53 (DNS管理)
- **負荷分散**: AWS Elastic Load Balancer (ELB)

### コミュニケーション
- **リアルタイム通信**: Node.js/websocket

## 必要条件
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL >= 5.7

## インストール
### 1. リポジトリのクローン:
任意のディレクトリに移動し、リポジトリをクローンし、vsCodeで開く
```bash
git clone git@github.com:If-you-give-up-then-it-all-ends-here/LINE-chat-app-manager.git
```
```bash
cd LINE-chat-app-manager
```

### 2. .envファイルの設定
.envファイルをルート直下に作成する
> [!IMPORTANT]
> envファイルの場所: `\\192.168.100.101\kyoyu\61.システム開発\Line連携チャットシステム\env\LINE-chat-app-manager`

### 3. 依存関係のインストール:
> [!IMPORTANT]
> phpの拡張機能、`fileinfo`を有効にしておく必要があります
> yarnのインストールも忘れずにおこなってください

必要なライブラリなどのインストール
```bash
composer install
```
```bash
npm install
```
```bash
npm install yarn --g
```

### 4. ビルド:
```bash
yarn mix
```

### 5. 開発サーバーの起動
すべてセットアップ出来たら、開発サーバーを起動する
```bash
php artisan serve
```

