# laravel-commons
再利用可能な Laravel コンポーネント

[toc]

## インストール
`composer.json` にリポジトリを追加する。

```json
"repositories": [          
  {                                                               
    "type": "vcs",                        
    "url": "git@github.com:grohiro/laravel-commons.git"
  }
]      
```

サービスプロバイダを登録する。

```php
// config/app.php
$providers = [
    \LaravelCommons\ServiceProvider::class
];
```

設定ファイルを作成する。

```bash
$ php artisan vendor:publish --provider=LaravelCommons\ServicePovider
# config/laravel_commons.php が生成される
```

## Logging

複数のロガーを追加します。

|ファイル|ログ|
|-------|---|
|request.log|アプリケーションが受け取る全てのリクエストとレスポンス|
|query.log|SQLログ|
|console.log|Command のログをファイルと`STDOUT`に出力する|
|http.log|外部APIを実行するときの HTTP リクエストとレスポンス|

### 使い方

#### request.log

Middleware を追加する。

```php
# app/Http/Kernel.php
protected $middleware = [
    \LaravelCommons\Logging\RequestLogger::class,
]
```

#### console.log

Laravel の ログファイル `storage/logs/console.log` と `STDOUT` にログを出力します。

```php
$logger = resolve('logger.console');
$logger->debug('hoge');
// Log::debug() これは STDOUT には出力されない
```

### オプション

config/laravel_commons.php

|Key|Type|Description|
|---|----|-----------|
|logging.request|boolean|request.log を使用する|
|logging.query|boolean|query.log を使用する|
|logging.http|boolean|http.log を使用する|

## SSL

SSL を強制します。`http://` へのアクセスを `https://` にリダイレクトします。
対象のルートグループは `web` と `api` です。

### オプション

|Key|Type|Description|
|---|----|-----------|
|ssl.environments|array[string]|SSL を使用する `environment` を指定する|
