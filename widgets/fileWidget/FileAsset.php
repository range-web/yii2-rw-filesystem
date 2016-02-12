<?php
namespace rangeweb\filesystem\widgets\fileWidget;

class FileAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/range-web/yii2-rw-filesystem/widgets/fileWidget/assets';

    public $css = [
        'js/jquery-file-upload/css/jquery.fileupload.css',
        'css/file.css',
    ];
    public $js = [
        'js/jquery-file-upload/js/vendor/jquery.ui.widget.js',
        'js/jquery-file-upload/js/jquery.iframe-transport.js',
        'js/jquery-file-upload/js/jquery.fileupload.js',
        'js/file.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];
}
