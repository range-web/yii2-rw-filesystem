<?php

namespace rangeweb\filesystem\models;

use rangeweb\filesystem\Module;
use vova07\imperavi\helpers\FileHelper;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "tbl_files".
 *
 * @property integer $id
 * @property string $owner
 * @property integer $owner_id
 * @property string $name
 * @property string $description
 * @property string $alt
 * @property string $file_name
 * @property string $original_name
 * @property string $subdir
 * @property string $size
 * @property string $mime_type
 * @property integer $tmp
 * @property integer $user_id
 * @property string $date_create
 */
class File extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tbl_files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['owner_id', 'tmp'], 'integer'],
            [['description'], 'string'],
            [['date_create'], 'safe'],
            [['owner', 'name', 'alt', 'file_name', 'original_name', 'subdir', 'size', 'mime_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner' => 'Owner',
            'owner_id' => 'Owner ID',
            'name' => 'Name',
            'description' => 'Description',
            'alt' => 'Alt',
            'file_name' => 'File Name',
            'original_name' => 'Original Name',
            'subdir' => 'Subdir',
            'size' => 'Size',
            'mime_type' => 'Mime Type',
            'tmp' => 'Tmp',
            'date_create' => 'Date Create',
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isNewRecord){
            $this->tmp = 1;
            $this->date_create = date('Y-m-d H-i-s');
            
            if ($this->user_id == null) {
                if (!\Yii::$app->user->isGuest) {
                    $this->user_id = Yii::$app->user->id;
                }
            
            }
        }
        
        

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->date_create = date('d.m.Y', strtotime($this->date_create));
        parent::afterFind();
    }

    /**
     * @param $id
     * @param $arSize
     * @param int $imageQuality
     * @return bool|array
     * @throws \yii\base\Exception
     */
    public static function getResizeImage($id, $arSize, $imageQuality=80)
    {
        if ($id == null || empty($arSize))
            return false;

        $res = [];

        $image = self::getFile($id);

        if (!$image)
            return false;

        $res['name'] = $image['name'];
        $res['alt'] = $image['alt'];

        $cachedImage = Yii::$app->cache->get('CachedImage_'.$arSize[0].'x'.$arSize[1].'_'.$id);

        if (!$cachedImage) {

            $path = FileHelper::normalizePath(Yii::getAlias(Yii::$app->getModule('filesystem')->uploadPath.Module::$cacheImagesPath)).DIRECTORY_SEPARATOR;
            $folder = $id.DIRECTORY_SEPARATOR .$arSize[0].'x'.$arSize[1].DIRECTORY_SEPARATOR;

            if (!file_exists($path.$folder.$image['file_name'])) {

                $originalFile = FileHelper::normalizePath(Yii::getAlias(Yii::$app->getModule('filesystem')->uploadPath.$image['subdir'].'/'.$image['file_name']));

                if (file_exists($originalFile) && FileHelper::createDirectory($path.$folder)) {

                    $imageGD=Yii::$app->image->load($originalFile);
                    $imageGD->resize($arSize[0],$arSize[1]);

                    $cachedImage['file'] = $imageGD->file;
                    $cachedImage['width'] = $imageGD->width;
                    $cachedImage['height'] = $imageGD->height;
                    $cachedImage['mime'] = $imageGD->mime;

                    $imageGD->save($path.$folder.$image['file_name'], $imageQuality);
                } else {
                    return false;
                }
            }

            $cachedImage['url'] = Module::$cacheImagesPath.$id.'/' .$arSize[0].'x'.$arSize[1].'/'.$image['file_name'];

            Yii::$app->cache->set('CachedImage_'.$arSize[0].'x'.$arSize[1].'_'.$id, $cachedImage);
        }

        $res = array_merge($res, $cachedImage);

        return $res;
    }

    public static function img($id, $arSize, $imageQuality=80, $options = [])
    {
        $image = self::getResizeImage($id, $arSize, $imageQuality);

        if (!$image)
            return '';

        $options['alt'] = $image['alt'];

        return Html::img($image['url'], $options);
    }

    public static function url($id, $arSize, $imageQuality=80)
    {
        $image = self::getResizeImage($id, $arSize, $imageQuality);

        if (!$image)
            return '';

        return $image['url'];
    }

    public static function getFile($id)
    {
        $file = Yii::$app->cache->get('Filesystem'.$id);

        if (!$file) {
            $file = self::find()
                ->where('id=:id', ['id'=>$id])
                ->asArray()
                ->one();

            if (!$file)
                return false;

            Yii::$app->cache->set('Filesystem'.$id, $file);
        }
        $file['src'] ='/'.$file['subdir'].'/'.$file['file_name'];
        return $file;
    }
}
