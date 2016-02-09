<?php
namespace rangeweb\filesystem\widgets\fileWidget;

use yii\base\Widget;

class FileWidget extends Widget
{
    public $model;
    public $attribute;
    public $htmlOptions = [];
    public $url = '/filesystem/default/upload';
    public $mimeTypes = '';
    public $multiple = false;

    public function run()
    {
        $classArray = $this->parseClassName($this->model);

        $this->htmlOptions['id'] = ucfirst($classArray['classname']).ucfirst($this->attribute);

        $this->registerClientScript();

        return $this->render('index', [
            'classArray' => $classArray
        ]);
    }



    public function registerClientScript()
    {
        $view = $this->getView();

        $view->registerJs(
            "$('#{$this->htmlOptions['id']}').fileupload({
                    url: $(this).data('url'),
                    dataType: 'json',
                    /* progressall: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        $('#progress .progress-bar').css(
                            'width',
                            progress + '%'
                        );
                        $('#progress').attr('data-percent', progress + '%');
                    },*/
                    fail: function (e, data) {}

                }).prop('disabled', !$.support.fileInput)
                    .parent().addClass($.support.fileInput ? undefined : 'disabled');

                $('#{$this->htmlOptions['id']}').on('fileuploadstop', function (e) {
                   /* $('#progress').attr('data-percent', 'Все файлы успешно загружены!');*/
                })
                .on('fileuploaddone', function (e, data) {
                     if (data.result.id > 0) {
                     formStepOne.append('<input type=\"hidden\" name=\"'+e.target.dataset.fieldname+'\" value=\"'+data.result.id+'\">')
                     }
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