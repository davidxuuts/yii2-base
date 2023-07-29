<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\assets;

use yii\web\AssetBundle;

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
