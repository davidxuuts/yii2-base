<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\assets;

use yii\web\AssetBundle;

class FancyUIAsset extends AssetBundle
{
    public $sourcePath = '@npm/fancyapps--ui/dist/';
    public $css = [
        'fancybox.css',
    ];
    public $js = [
        'fancybox.umd.js',
    ];

    public $depends = [
        BaseAppAsset::class,
    ];
}
