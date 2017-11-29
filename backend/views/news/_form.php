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

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(CKEditor::className(),[
        'editorOptions' => ElFinder::ckeditorOptions(['elfinder'],[/* Some CKEditor Options */]),
    ]) ?>

    <?php 
        $initialPreview = $model->isNewRecord || !$model->image ? [] : 
        [Html::img(Yii::$app->urlManagerFrontend->createAbsoluteUrl(Yii::$app->imageFiles->src($model, 200, 200), true))] 
    ?>
        
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
    
    <?php if (!$model->isNewRecord): ?>
        
        <?php 
            
            $initialPreview = [];
            $initialPreviewConfig = [];
            
            foreach ($model->files as $file) {
                $initialPreview[] = Html::a(
                        $file->title,
                        Yii::$app->urlManagerFrontend->createAbsoluteUrl(Yii::$app->files->getUrlFile($file->title))
//                        Url::to([Yii::$app->files->getUrlFile($file->title)])
                    );
                
                $initialPreviewConfig[] = [
                    'url' => Url::to(['/news/delete-attach-file']),
                    'key' => $file->id,
                    'extra' => ['owner_id' => $model->id],
                ];
            }
        ?>
    
        <?= $form->field($model, 'attach[]')->widget(FileInput::classname(), [
            'options' => [
                'multiple' => true, 
                'accept' => 'application/pdf, application/zip, application/doc'
            ],
            'pluginOptions' => [
                'previewFileType' => 'any',
                'uploadUrl' => Url::to(['/news/file-upload']),
                'allowedFileExtensions' => ['pdf','zip','doc'],
                'uploadExtraData' => [
                    'owner_id' => $model->id,
                ],
                'maxFileSize' => 4 * 1024,
                
                'initialPreview' => $initialPreview, 
                'initialPreviewConfig' => $initialPreviewConfig,
            ]
        ]); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
