<?php

namespace davidxu\base\assets;

use davidxu\sweetalert2\assets\SweetAlert2Asset;
use davidxu\sweetalert2\assets\SweetConfirmAsset;
use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class BaseAppAsset
 * @package davidxu\base\assets;
 */
class BaseAppAsset extends AssetBundle
{
    public $sourcePath = '@davidxu/base/';
    /**
     * @var array
     */
    public $js = [
        'js/common.js',
    ];
    public $css = [];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FontAwesomeAsset::class,
        SweetAlert2Asset::class,
        SweetConfirmAsset::class,
    ];
}
