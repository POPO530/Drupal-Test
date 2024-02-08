## 必要なパッケージ
- Webform
- Webform Email Reply
- SMTP Authentication Support

## インストールコマンド

### docker-compose

```bash
docker-compose up -d
```

### php コンテナに入る

```bash
docker exec -it drupal bash
# or
docker-compose exec drupal bash
```

---


## Drupalプロジェクトを新規作成する場合

### drupal のインストール

```bash
// Drupal9をインストールする場合
composer create-project drupal/recommended-project:9.5.11 .
```

1. `localhost`へアクセス

2. Choose language -> 「日本語」を選択

3. インストールプロフィールを選んでください -> 「標準」を選択

4. 設定ファイルのエラーがでている

- `./sites/default/default.settings.php` をコピーして `./sites/default/settings.php` を作成
- パーミッション変更してを書き込めるように変更

```bash
chmod -R 777 ./
```

5. データベースの構成

- 「Sqlite」を選択して進める。
