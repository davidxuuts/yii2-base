<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

use common\models\common\Attachment;
use davidxu\base\helpers\StringHelper;
use yii\helpers\Html;

/**
 * @var Attachment $model
 */
?>

<span class="mailbox-attachment-icon has-img no-img">
    <?= Html::img($model->path, [
        'class' => 'img-fluid img-thumbnail',
        'alt' => Html::encode($model->name),
    ]) ?>
</span>
<div class="mailbox-attachment-info">
    <span class="mailbox-attachment-name selector-img-name">
        <i class="fas fa-paperclip"></i>
        <?= Html::encode($model->name) ?>
    </span>
    <span class="mailbox-attachment-size clearfix mt-1">
        <span><?= StringHelper::getSizeInByte($model->size) ?></span>
    </span>
</div>

