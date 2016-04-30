<div class="rw-file-input <?=($this->context->required)?'required':''?>">
    <div class="info-upload-files" data-field-delete-name="<?= $classArray['classname']?>[<?= $this->context->attribute?>_delete][]" <?=(count($this->context->file) > 1)?'style="display: block"':''?>  >
        <?php if (count($this->context->file) > 1) : ?>
            <?php foreach ($this->context->file as $file) : ?>
                <div class="file-item"  data-file-id="<?=$file['id']?>"><?=$file['original_name']?><span class="remove-file">×</span></div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="input-group">
        <div tabindex="500" class="pseudo-input file-caption  kv-fileinput-caption">
            <div class="file-caption-name"  data-placeholder="<?=($this->context->placeholder)?$this->context->placeholder:''?>"><?=($this->context->placeholder)?$this->context->placeholder:''?></div>
            <div class="progress" style="display: none">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="0" style="width: 0%">
                    <span class="sr-only"></span>
                </div>
            </div>
        </div>
        <div class="input-group-btn">
            <button type="button" tabindex="500" title="Clear selected files" data-remove-url="<?= \yii\helpers\Url::to([$this->context->removeUrl]) ?>" class="btn btn-default fileinput-remove fileinput-remove-button" <?=(!empty($this->context->file)) ? '':'style="display:none"'?>>
                <i class="<?=$this->context->htmlOptions['btn-remove-icon']?>"></i>
            </button>'
            <div tabindex="500" class="btn btn-primary btn-file">
                <i class="<?=$this->context->htmlOptions['btn-upload-icon']?>"></i>
                <span class="hidden-xs">Выбрать</span>
                <input id="<?=$this->context->htmlOptions['id']?>" data-fieldName="<?= $classArray['classname']?>[<?= $this->context->attribute?>][]" data-url="<?= \yii\helpers\Url::to([$this->context->url]) ?>" accept="<?= $this->context->mimeTypes?>" type="file" name="files[]" <?=($this->context->multiple)?'multiple':''?>>
            </div>
        </div>
    </div>
    <?php if (!empty($this->context->file)) : ?>
       <?php foreach ($this->context->file as $file) : ?>
            <input class="<?=$this->context->htmlOptions['id']?> form-control update-file" type="hidden" data-file="<?=$file['id']?>" data-title="<?=$file['original_name']?>" name="<?= $classArray['classname']?>[<?= $this->context->attribute?>_update]" value="<?=$file['id']?>">
       <?php endforeach; ?>
    <?php endif; ?>
</div>



