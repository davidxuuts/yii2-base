<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;

class JqueryCropperAsset extends AssetBundle
{
    public $sourcePath = '@npm/jquery-cropper/dist';
    public $js = [
        'jquery-cropper' . (YII_ENV_PROD ? '.min' : '') . '.js',
    ];
    public $css = [
    ];

    public $depends = [
        CropperAsset::class,
    ];
}
