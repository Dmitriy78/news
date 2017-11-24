<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\News;

/**
 * Description of CleanDraftController
 *
 * @author dima
 */
class CleanDraftController extends Controller
{
    /**
     * 
     */
    public function actionIndex()
    {
        $models = News::deleteAll(['draft' => News::IS_DRAFT]);
    }
}
