<?php
namespace rangeweb\filesystem\controllers;
use Yii;
use yii\web\Controller;


class DefaultController extends Controller
{

    public function actions()
  {
      return [
          'upload' => [
              'class' => 'rangeweb\filesystem\actions\UploadAction',
          ]
      ];
  }

}