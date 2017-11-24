<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel common\models\search\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'News';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create News', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id',
                'options' => ['width' => '70px'],
            ],
            'title',
            [
                'attribute' => 'count_attach',
                'value' => function($model) {
                    return $model->getFiles()->count();
                },
            ],
//            'text:ntext',
//            'image',
            'active:boolean',
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'options' => ['width' => '200px'],
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'created_at',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]),
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'options' => ['width' => '200px'],
                'filter' => DatePicker::widget([
                    'model'      => $searchModel,
                    'attribute'  => 'updated_at',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                    ],
                ]),
            ],
              
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width'=>'90px'],
                'template' => '{view} {update} {delete} {changeActive}',
                'buttons' => [
                    'changeActive' => function ($url, $model, $key) {
        
                        if ($model->active) {
                            $icon = 'glyphicon glyphicon-minus-sign';
                            $title = 'Снять с публикации';
                        } else {
                            $icon = 'glyphicon glyphicon-plus-sign';
                            $title = 'Опубликовать';
                        }
                        return Html::a('<span class="'. $icon .'"></span>', 
                        Url::to(['/news/change-active', 'id' => $model->id]),
                        [
                            'title' => $title,
                            'aria-label' => $title,
                            'data-method' => 'post',
                            'data-confirm' => 'Вы уверены, что хотите изменить статус?',
                            'data-pjax' => 0              
                        ]);
                    }
                ],
            ],
        ],
    ]); ?>
</div>
