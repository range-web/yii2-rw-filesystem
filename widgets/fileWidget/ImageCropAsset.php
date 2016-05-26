<?php
namespace rangeweb\filesystem\widgets\fileWidget;

class FileAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/range-web/yii2-rw-filesystem/widgets/fileWidget/assets';

    public $css = [
        'css/jcrop.min.css',
    ];
    public $js = [
        'js/jquery.Jcrop.min.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];
}
