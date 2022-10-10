<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;

class JqueryCropperJsAsset extends AssetBundle
{
    public $sourcePath = '@davidxu/base';
    public $js = [
        'js/jquery-cropper-js' . (YII_ENV_PROD ? '.min' : '') . '.js',
    ];
    public $css = [
        'css/jquery-cropper' . (YII_ENV_PROD ? '.min' : '') . '.css',
    ];

    public $depends = [
        JqueryCropperAsset::class,
    ];
}
