<?php
namespace rangeweb\filesystem\controllers;
use Yii;
use yii\web\Controller;
use rangeweb\filesystem\models\File;
use yii\web\Response;

class DefaultController extends Controller
{
    public function actions()
    {
      return [
          'upload' => [
              'class' => 'rangeweb\filesystem\actions\UploadAction',
          ],
          'delete' => [
              'class' => 'rangeweb\filesystem\actions\DeleteAction',
          ]
      ];
    }
}