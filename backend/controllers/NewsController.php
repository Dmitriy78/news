<?php

namespace backend\controllers;

use Yii;
use common\models\News;
use common\models\search\NewsSearch;
use backend\controllers\BaseAdminController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends BaseAdminController
{
    /**
     * Lists all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single News model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new News model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new News();
        
        $model->active = true;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            Yii::$app->imageFiles->upload($model, 'image');
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing News model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            
            Yii::$app->imageFiles->upload($model, 'image');
            
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
            
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing News model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if ($model) {
            Yii::$app->imageFiles->delete($model);
            $model->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return News the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 
     * @param type $id
     * @return type
     */
    public function actionChangeActive($id)
    {
        $model = $this->findModel($id);
        
        $model->updateAttributes(['active' => !$model->active]);
        
        return $this->redirect(['index']);
    }
    
    public function actionDeleteImage()
    {
        $id = (int)\Yii::$app->request->post('key'); 
        
        $model = $this->findModel($id);
        
        if ($model) {
            Yii::$app->imageFiles->delete($model);  
            $model->updateAttributes(['image' => '']);
        }
        return '{}';
    }
}