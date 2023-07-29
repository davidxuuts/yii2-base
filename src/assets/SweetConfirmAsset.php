<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

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
