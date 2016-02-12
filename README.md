Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist rangeweb/yii2-filesystem "*"
```

or add

```
"rangeweb/yii2-filesystem": "*"
```

to the require section of your `composer.json` file.

Apply migration
```sh
yii migrate --migrationPath=vendor/range-web/yii2-rw-filesystem/migrations
```

Configuration:

```php
'modules' => [
    'filesystem' => [
        'class' => 'rangeweb\filesystem\Module',
    ],
],
```
