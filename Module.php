<?php

namespace rangeweb\filesystem;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'rangeweb\filesystem\controllers';

    /**
     * @var array upload routes
     */
    public static $cacheImagesPath = '/uploads/cache/images/';

    /**
     * @var array max thumbnail size.
     */
    public $maxThumbsImages = [1920, 1080];


    /**
     * @var array default thumbnail size, using in filemanager view.
     */
    private static $defaultThumbSize = [128, 128];

    public function init()
    {
        parent::init();
    }

    /**
     * @return array default thumbnail size.
     */
    public static function getDefaultThumbSize()
    {
        return self::$defaultThumbSize;
    }

}
