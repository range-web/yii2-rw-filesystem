<?php

namespace rangeweb\filesystem\actions;

use Yii;
use yii\base\DynamicModel;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use \yii\web\Response;
use yii\web\UploadedFile;
use yii\base\Action;
use yii\helpers\FileHelper;
use rangeweb\filesystem\models\File;

/**
 * UploadAction for images and files.
 *
 * Usage:
 * ```php
 * public function actions()
 * {
 *      return [
 *          'upload' => [
 *              'class' => 'rangeweb\filesystem\actions\UploadAction',
*          ]
 *      ];
 * }
 *
 * ```
 */
class UploadAction extends Action
{
    private $_validator = 'image';
    public $validatorOptions = [];
    public $uploadOnlyImage = false;
    public $path;
    public $uploadPath;

    public $callback;

    public function init()
    {
        $this->path = 'uploads/'. $this->path;
        $separator = ($this->path) ? DIRECTORY_SEPARATOR : '';
        $this->uploadPath = FileHelper::normalizePath(Yii::getAlias(Yii::$app->getModule('filesystem')->uploadPath . $this->path)) . $separator;

        if (!FileHelper::createDirectory($this->uploadPath)) {
            throw new InvalidCallException("Directory specified in 'path' attribute doesn't exist or cannot be created.");
        }
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if (Yii::$app->request->isPost) {

            $file = UploadedFile::getInstanceByName('files[0]');

            $model = new DynamicModel(compact('file'));

            $model->addRule('file', $this->_validator, $this->validatorOptions)->validate();

            if ($model->hasErrors()) {
                $result = [
                    'error' => $model->getFirstError('file')
                ];
            } else {
                if ($model->file->extension) {
                    $model->file->name = uniqid() . '.' . $model->file->extension;
                }

                if (!file_exists($this->uploadPath)) {
                    FileHelper::createDirectory($this->uploadPath);
                }

                if ($model->file->saveAs($this->uploadPath . $model->file->name)) {
                    $result = [
                        'originalName' => $_FILES["files"]['name'][0],
                        'name' => $model->file->name
                    ];
                } else {
                    $result = ['error' => 'ERROR_CAN_NOT_UPLOAD_FILE'];
                }
            }

            $fileModel = new File();

            $fileModel->file_name = $file->name;
            $fileModel->original_name = $_FILES["files"]['name'][0];
            $fileModel->size = strval($file->size);

            $fileModel->mime_type = $file->type;
            $fileModel->subdir = $this->path;

            $result['url'] = '/'.$fileModel->subdir.'/'.$fileModel->file_name;

            $result['id'] = 0;
            $fileModel->validate();
            if ($fileModel->save()){
                $result['id'] = $fileModel->id;
            }
            if ($this->callback != null) {
                $this->callback($fileModel->id);
            }

            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }
    }


    /**
     * @param $imageId
     * @return mixed
     * @throws InvalidConfigException
     */
    protected function callback($imageId)
    {
        if (!is_callable($this->callback)) {
            throw new InvalidConfigException('"' . get_class($this) . '::callback" should be a valid callback.');
        }
        $response = call_user_func($this->callback, $imageId);
        return $response;
    }
}
