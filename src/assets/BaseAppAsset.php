<?php

namespace davidxu\base\assets;

use davidxu\base\assets\SweetAlert2Asset;
use davidxu\base\assets\SweetConfirmAsset;
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
        'js/jquery.i18n.js',
    ];
    public $css = [];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FontAwesomeAsset::class,
        SweetConfirmAsset::class,
    ];
}
