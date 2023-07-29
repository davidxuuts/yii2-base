<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

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
