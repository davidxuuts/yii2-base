<?php

namespace davidxu\base\assets;

use yii\web\AssetBundle;

/**
 * Class SweetConfirmAsset
 * @package davidxu\base\assets
 */
class SweetConfirmAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@davidxu/base/';

    /**
     * @var array
     */
    public $css = [];

    /**
     * @var array
     */
    public $js = [
        'js/sweetconfirm.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        SweetAlert2Asset::class,
    ];
}
