<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;

class QETagAsset extends AssetBundle
{
    public $sourcePath = '@davidxu/base/';
    public $js = [
        'js/sha1.min.js',
        'js/qetag.js',
    ];
    public $css = [
    ];
}
