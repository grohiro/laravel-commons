# laravel-commons
再利用可能な Laravel コンポーネント

[toc]

## インストール

```bash
$ composer config repositories.grohiro/laravel-commons vcs https://github.com/grohiro/laravel-commons.git
$ composer require grohiro/laravel-commons:dev-master

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

### オプション

|Key|Type|Description|
|---|----|-----------|
|ssl.environments|array[string]|SSL を使用する `app.env` を指定する|

```php
'ssl' => [
  'environments' => ['staging', 'production]
]
```

## Enum

`id` と `label` を持った `Enum` クラスを追加します。

```php
<?php
namespace App\Types;

use LaravelCommons\Enum\Enum;

/**
 * 年齢層
 */
class AgeRange extends Enum
{
    public static $All;
    public static $Early20;
    public static $Late20;
    public static $Early30;
    public static $Late30;
    public static $Over40;

    public static $Values;

    /**
     * 初期化
     */
    public static function init()
    {
        self::$All     = new AgeRange(1, '指定なし');
        self::$Early20 = new AgeRange(2, '20代前半');
        self::$Late20  = new AgeRange(3, '20代後半');
        self::$Early30 = new AgeRange(4, '30代前半');
        self::$Late30  = new AgeRange(5, '30代後半');
        self::$Over40  = new AgeRange(6, '40代以上');

        self::$Values = [
            self::$All,
            self::$Early20,
            self::$Late20,
            self::$Early30,
            self::$Late30,
            self::$Over40,
        ];
    }
}

// initialize in AppServiceProvider
AgeRange::init();

// accessor
$all = AgeRange::valueOf(1); // 指定なし
$notFound = AgeRange::valueOf(1000); // => null
foreach (AgeRange::$Values as $value) {
  if ($value->id === 3) {
      echo $value->label;
  }
}

// mutator
class User extends Model
{
  function setAgeRangeAttribute($ageRange)
  {
    if ($ageRange !== null) {
      $this->attributes['age_range_id'] = $ageRange->id;
    } else {
      $this->attributes['age_range_id'] = null;
    }
  }
  function getAgeRangeAttribute()
  {
    return AgeRange::valueOf($this->attributes['age_range_id']);
  }
}
// show AgeRange of user
echo $user->ageRange->label;
```

文字列のコード値でアクセスしたいときは `WithCode` トレイトを使用します。

```php
class Platform extends Enum {
  use WithCode;
  
  public static $Ios;
  public static $Android;
  public static $Values;

  public function __construct($id, $label, $code)
  {
    parent::__construct($id, $label);
    $this->code = $code;
  }

  public static function init() {

    self::$Ios = new Platform(1, 'iOS', 'ios');
    self::$Android = new Platform(2, 'Android', 'android');
    
    self::$Values = [
      self::$Ios,
      self::$Android,
    ];
  }
}

// initialize
Platform::init();

// accessor
$ios = Platform::valueOfCode('ios');
echo $ios->label;
```