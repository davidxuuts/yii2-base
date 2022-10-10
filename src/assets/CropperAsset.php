<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;

class CropperAsset extends AssetBundle
{
    public $sourcePath = '@npm/cropperjs/dist';
    public $js = [
        'cropper' . (YII_ENV_PROD ? '.min' : '') . '.js',
    ];
    public $css = [
        'cropper' . (YII_ENV_PROD ? '.min' : '') . '.css',
    ];

    public $depends = [
        BaseAppAsset::class,
    ];
}
