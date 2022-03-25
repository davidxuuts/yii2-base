<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;
use yii\web\YiiAsset;

/**
 * Class FontAwesomeAsset
 * @package davidxu\base\assets;
 */
class FontAwesomeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/fortawesome/font-awesome/';

    /**
     * @var array
     */
    public $js = [];
    public $css = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        $min = YII_ENV_DEV ? '' : '.min';
        $this->css[] = 'css/all' . $min . '.css';
        $this->js[] = 'js/all' . $min . '.js';
    }
}
