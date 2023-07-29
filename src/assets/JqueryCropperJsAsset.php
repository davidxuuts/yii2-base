<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

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
