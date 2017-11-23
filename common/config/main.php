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
    'controllerMap' => [
        'elfinder' => [
            'class' => 'mihaildev\elfinder\PathController',
            'access' => ['@'],
            'root' => [
                'baseUrl' => '',
                'basePath' => '@frontend',
                'path' => '/uploads/',
                'name' => 'Файлы'
            ],
//            'watermark' => [
//                        'source'         => __DIR__.'/logo.png', // Path to Water mark image
//                         'marginRight'    => 5,          // Margin right pixel
//                         'marginBottom'   => 5,          // Margin bottom pixel
//                         'quality'        => 95,         // JPEG image save quality
//                         'transparency'   => 70,         // Water mark image transparency ( other than PNG )
//                         'targetType'     => IMG_GIF|IMG_JPG|IMG_PNG|IMG_WBMP, // Target image formats ( bit-field )
//                         'targetMinPixel' => 200         // Target image minimum pixel size
//            ]
        ]
    ],
];
