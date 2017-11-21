<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\News */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions(['elfinder'],[/* Some CKEditor Options */]),
    ]) ?>

    <?php $initialPreview = $model->isNewRecord || !$model->image ? 
            [] : [Html::img(Yii::$app->imageFiles->src($model, 200, 200), [])] ?>
        
    <?= $form->field($model, 'image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showUpload' => false,
            
            'fileActionSettings' => [
//                'removeIcon' => '<span class="icon">delete</span> ',
                'showZoom' => false,
            ],
            'initialPreview' => $initialPreview, 
            'initialPreviewConfig'=> [
                [
//                    'catpion' => '...',
                    'url' => Url::to(['/news/delete-image']),
                    'key' => $model->id,
                    'extra' => ['id' => $model->id],
                ]
            ],
           
        ]
    ]); ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
