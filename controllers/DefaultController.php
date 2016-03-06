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
          ]
      ];
    }

    public function actionDelete()
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
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $result;
    }
}