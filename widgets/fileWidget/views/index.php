
<div class="row">
    <div class="col-lg-1" style="width:12%;height: 38px">
    <span class="btn btn-success btn-xs fileinput-button">
        <i class="glyphicon glyphicon-plus"></i>
        <span>Выбрать файлы</span>
        <input id="<?=$this->context->htmlOptions['id']?>" data-fieldName="<?= $classArray['classname']?>[<?= $this->context->attribute?>][]" data-url="<?= \yii\helpers\Url::to([$this->context->url]) ?>" accept="<?= $this->context->mimeTypes?>" type="file" name="files[]" <?=($this->context->multiple)?'multiple':''?>>
    </span>
    </div>
    <div class="col-lg-11" style="width:88%;padding-top: 4px">
        <!--div id="progress" class="progress progress-striped" data-percent="0%" style="display: none">
            <div class="progress-bar progress-bar-success"></div>
        </div-->
    </div>
</div>