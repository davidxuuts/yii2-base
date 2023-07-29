<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

/**
 * @var ActiveDataProvider $dataProvider
 */

?>
<div class="container-fluid">
    <?php
    try {
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_selector-item',
            'options' => ['class' => 'row list-view'],
            'summaryOptions' => [
                'class' => 'col-12 pb-2'
            ],
            'itemOptions' => [
                'class' => 'col-xs-1 col-md-2 col-sm-3 select-img'
            ],
            'viewParams' => [
                'fullView' => true,
            ],
        ]);
    } catch (Exception|Throwable $e) {
        echo YII_DEBUG ? $e->getMessage() : null;
    } ?>
</div>
