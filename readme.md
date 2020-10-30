# nonono-utils

個人的に php で使う基本処理を集めたやつ。

## インストール

composer.json で下記のように指定する

```json
{
    (中略)
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:irokaru/nonono-utils.git"
        }
    ]
}
```

## ライブラリ

- Validator
  - 簡易的かつ汎用的なバリデータ

## 使い方

### Validator

```php
<?php
use nonono\Validator;

$data = {
    'name' => 'taro',
    'age'  => 20,
};

$v = new Validator($data);
$v->rule('name', {'type' => 'string', 'min' => 1, 'max' => 20});
$v->rule('age',  {'type' => 'int',    'min' => 0});

if ($v->exec()) {
    print('OK');
} else {
    print('NG');
    var_dump($v->errors());
}
```

## 開発用composerコマンド

### 整形

php-cs-fixerを使う。設定は`.php_cs`に。

```bash
# ドライラン
composer fixer:dry

# 実行
composer fixer
```

### テスト

coverageディレクトリが生成されてカバレッジが確認できる。

```bash
composer test:coverage
```