<?php
return [
    'language' => 'ru',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'imageFiles' => [
            'class' => 'common\components\ImageFiles',
            'alias' => '@frontend/',
            'attribute' => 'image',
            'folder_upload' => 'uploads',
            'folder_thumbs' => 'thumbs',
            'entity_folder' => true,
            'type_name' => 3,
        ],
        'files' => [
            'class' => 'common\components\Files',
            'alias' => '@frontend/',
            'attribute' => 'attach',
            'folder_upload' => 'uploads',
            'entity_folder' => false,
            'type_name' => 1,
        ],
    ],
];
