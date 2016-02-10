<div class="rw-file-input">
    <div class="info-upload-files">

    </div>
    <div class="input-group">
        <div tabindex="500" class="form-control file-caption  kv-fileinput-caption">
            <div class="file-caption-name"></div>
        </div>
        <div class="input-group-btn">
            <button type="button" tabindex="500" title="Clear selected files" data-remove-url="<?= \yii\helpers\Url::to([$this->context->removeUrl]) ?>" class="btn btn-default fileinput-remove fileinput-remove-button" style="display:none">
                <i class="<?=$this->context->htmlOptions['btn-remove-icon']?>"></i>
            </button>
            <div tabindex="500" class="btn btn-primary btn-file">
                <i class="<?=$this->context->htmlOptions['btn-upload-icon']?>"></i>
                <span class="hidden-xs">Выбрать …</span>
                <input id="<?=$this->context->htmlOptions['id']?>" data-fieldName="<?= $classArray['classname']?>[<?= $this->context->attribute?>][]" data-url="<?= \yii\helpers\Url::to([$this->context->url]) ?>" accept="<?= $this->context->mimeTypes?>" type="file" name="files[]" <?=($this->context->multiple)?'multiple':''?>>
            </div>
        </div>
    </div>
</div>



    