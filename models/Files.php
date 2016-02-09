<?php

namespace rangeweb\filesystem\models;

use Yii;

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
 * @property string $date_create
 */
class Files extends \yii\db\ActiveRecord
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
        }

        return parent::beforeSave($insert);
    }

    public function afterFind()
    {
        $this->date_create = date('d.m.Y', strtotime($this->date_create));
        parent::afterFind();
    }
}
