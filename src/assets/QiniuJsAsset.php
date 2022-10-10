<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;

class QiniuJsAsset extends AssetBundle
{
    public $sourcePath = '@npm/qiniu-js/dist/';
    public $css = [
    ];
    public $js = [
        'qiniu.min.js',
    ];

    public $depends = [
        BaseAppAsset::class,
    ];
}
