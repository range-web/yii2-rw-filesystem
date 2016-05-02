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
class DeleteAction extends Action
{
    private $_validator = 'image';
    public $validatorOptions = [];
    public $uploadOnlyImage = false;
    public $path;
    public $uploadPath;

    public $callback;

    public function init()
    {
        
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $result = ['status'=>false];
        if (Yii::$app->request->isPost && isset($_POST['id'])) {

            if (is_array($_POST['id'])) {
                foreach ($_POST['id'] as $id) {
                    File::find()
                        ->where('id = :id', ['id'=>$id])
                        ->one()
                        ->delete();
                }
            } else {

                File::find()
                    ->where('id = :id', ['id'=>$_POST['id']])
                    ->one()
                    ->delete();
            }
            $result['status'] = true;


            $this->callback($_POST['id']);
        }
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
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
