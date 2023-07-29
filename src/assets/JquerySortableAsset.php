<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class JquerySortableAsset extends AssetBundle
{
    public $sourcePath = '@npm/jquery-sortablejs';
    public $js = [
        'jquery-sortable' . (YII_ENV_PROD ? '.min' : '') . '.js',
    ];
    public $css = [
    ];

    public $depends = [
        JqueryAsset::class,
    ];
}
