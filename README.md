# 🔐 ログイン情報（テストアカウント）

メールアドレス: test@test.com<br>
パスワード: password1234

- 上記のテストアカウントでログイン可能です。
- 新規アカウントを登録してログインすることもできます。

# 環境構築手順

このドキュメントでは、ローカル環境での Laravel プロジェクトのセットアップ手順を説明します。

## 1. `.env` ファイルの作成

まず、`laravel` フォルダに移動し、`.env.example` をコピーして `.env` ファイルを作成します。

```bash
cp .env.example .env
```

## 2. Google OAuth クライアント ID とシークレットキーの取得

1. [Google Cloud Console](https://console.cloud.google.com/project) でプロジェクトを作成します。
2. 作成したプロジェクトの「OAuth 2.0 クライアント ID」を作成し、「承認済みのリダイレクト URI」に以下の URL を設定します：http://localhost/login/google/callback
3. クライアント ID と シークレットキーを取得します。
4. `.env` ファイルに、取得したクライアント ID と シークレットキーを設定します：

```env
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
```

## Laradock 環境設定の手順

以降の手順はすべて `laradock` フォルダ内で実行してください。

## 3. Laradock 環境の設定

`.env.example` をコピーして `.env` ファイルを作成します。

```bash
cp .env.example .env
```

## 4. Docker コンテナの起動

Docker コンテナを起動します。

```bash
docker-compose up -d
```

## 5. Laravel の依存関係のインストール

コンテナ内で Composer を使用して Laravel の依存パッケージをインストールします。

```bash
docker-compose exec workspace composer install
```

## 6. アプリケーションキーの生成

アプリケーションのキーを生成します。

```bash
docker-compose exec workspace php artisan key:generate
```

## 7. データベースのマイグレーション

データベースのマイグレーションを実行して、必要なテーブルを作成します。

```bash
docker-compose exec workspace php artisan migrate
```

## 8. ストレージのシンボリックリンク作成

`public/storage` にシンボリックリンクを作成します。

```bash
docker-compose exec workspace php artisan storage:link
```

## 9. NPM パッケージのインストール

必要な NPM パッケージをインストールします。

```bash
docker-compose exec workspace npm install
```

## 10. 開発環境でのビルド

開発環境用にフロントエンドのビルドを実行します。

```bash
docker-compose exec workspace npm run dev
```

## 11. キューのワーカーを開始

別のターミナルを開いて、キューを処理するワーカーを起動します。

```bash
docker-compose exec workspace php artisan queue:work
```
