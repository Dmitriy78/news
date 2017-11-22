<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\News */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'News', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Список', ['index'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'text:html',
            [
                'attribute' => 'image',
                'format' => 'raw',
                'value' => ($model->image)? Html::img(Yii::$app->imageFiles->src($model, 100), ['alt'=>'', 'title'=>''])
                            : ''
            ],
            'active:boolean',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'attribute' => 'attach',
                'format' => 'raw',
                'value' => function($model) {
        
                    $list = [];
                    foreach ($model->files as $file) {
                        $list[] = Html::a($file->title, Url::to([Yii::$app->files->getUrlFile($file->title)]));
                    }
                    return implode('<br>', $list);
                }
            ]
            
        ],
    ]) ?>

</div>
