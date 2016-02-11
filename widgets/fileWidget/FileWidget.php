<?php
namespace rangeweb\filesystem\widgets\fileWidget;

use rangeweb\filesystem\models\File;
use yii\base\Widget;

class FileWidget extends Widget
{
    public $model;
    public $attribute;
    public $htmlOptions = [
        'btn-upload-icon' => 'glyphicon glyphicon-folder-open',
        'btn-remove-icon' => 'glyphicon glyphicon-trash'
    ];
    public $url = '/filesystem/default/upload';
    public $removeUrl = '/filesystem/default/delete';

    public $mimeTypes = '';
    public $multiple = false;

    public function run()
    {
        $classArray = $this->parseClassName($this->model);

        $this->htmlOptions['id'] = ucfirst($classArray['classname']).ucfirst($this->attribute);

        $this->registerAssetBundle();
        $this->registerClientScript();

        return $this->render('index', [
            'classArray' => $classArray
        ]);
    }


    public function registerAssetBundle()
    {
        $view = $this->getView();
        FileAsset::register($view);
    }

    public function registerClientScript()
    {
        $view = $this->getView();

        $view->registerJs(
            "jQuery('#{$this->htmlOptions['id']}').fileupload({
                    url: jQuery(this).data('url'),
                    dataType: 'json',
                    progressall: function (e, data) {
                        rwFileInput.progress(e.target, data);
                    },
                    stop: function(e) {
                        rwFileInput.uploadDone(e.target);
                    },
                    fail: function (e, data) {}

                }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');

                jQuery('#{$this->htmlOptions['id']}').on('fileuploadstop', function (e) {
                    //$('.progress').attr('data-percent', 'Все файлы успешно загружены!');
                })
                .on('fileuploaddone', function (e, data) {
                    rwFileInput.addFileInfo(e.target, data.result);
                });"
        );

    }

    function parseClassName($model)
    {
        $className = get_class($model);
        return array(
            'namespace' => array_slice(explode('\\', $className), 0, -1),
            'classname' => join('', array_slice(explode('\\', $className), -1)),
        );
    }
}