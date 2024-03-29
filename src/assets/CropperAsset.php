<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

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
