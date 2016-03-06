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
        'btn-remove-icon' => 'glyphicon glyphicon-trash',
    ];

    public $file = [];

    public $placeholder = false;
    public $url = '/filesystem/default/upload';
    public $removeUrl = '/filesystem/default/delete';

    public $mimeTypes = '';
    public $multiple = false;
    public $required = false;

    public $jsCallbackFunctionDone = '';
    public $jsCallbackFunctionAfterDelete = '';

    public function run()
    {
        if ($this->model != null && $this->attribute != null) {
            if (count($this->model->{$this->attribute})>1) {
                foreach($this->model->{$this->attribute} as $id=>$file_id) {
                    $this->file[] = File::getFile($file_id);
                }
                $this->placeholder = count($this->model->{$this->attribute}) . ' файла';
            } elseif ($this->model->{$this->attribute}>0) {
                // делаем запрос на информацию о файле
                $this->file[] = File::getFile($this->model->{$this->attribute});
                $this->placeholder = $this->file[0]['original_name'];
            }

            $classArray = $this->parseClassName($this->model);
            $this->htmlOptions['id'] = ucfirst($classArray['classname']).ucfirst($this->attribute);
        } else {
            $classArray = [
                'classname' => 'File',
            ];

            $this->attribute = 'file';

            $this->htmlOptions['id'] = 'File-'.uniqid();
        }

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

        $jsCallbackFunctionAfterDelete = 'function() {'.$this->jsCallbackFunctionAfterDelete.'}';

        $view->registerJs(
            "
            rwFileInput.callBackAfterDelete = {$jsCallbackFunctionAfterDelete};
            jQuery('#{$this->htmlOptions['id']}').fileupload({
                    url: jQuery(this).data('url'),
                    dataType: 'json',
                    progressall: function (e, data) {
                        rwFileInput.progress(e.target, data);
                    },

                    stop: function(e) {
                        rwFileInput.uploadDone(e.target);
                        {$this->jsCallbackFunctionDone}
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
        return [
            'namespace' => array_slice(explode('\\', $className), 0, -1),
            'classname' => join('', array_slice(explode('\\', $className), -1)),
        ];
    }
}