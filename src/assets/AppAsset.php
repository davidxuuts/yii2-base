<?php

namespace davidxu\base\assets;

use yii\bootstrap4\BootstrapAsset;
use yii\bootstrap4\BootstrapPluginAsset;
use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class AppAsset
 * @package davidxu\base\assets;
 */
class AppAsset extends AssetBundle
{
    /**
     * @var array
     */
    public $js = [];
    public $css = [];

    public $depends = [
        YiiAsset::class,
        BootstrapAsset::class,
        BootstrapPluginAsset::class,
        FontAwesomeAsset::class,
    ];
}
