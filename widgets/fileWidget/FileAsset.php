<?php
namespace rangeweb\filesystem\widgets\fileWidget;

class FileAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/range-web/yii2-rw-filesystem/widgets/fileWidget/assets';

    public $css = [
        'css/file.css'
    ];
    public $js = [
        'js/file.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'frontend\assets\ThemeBootstrapAsset',
    ];
}
