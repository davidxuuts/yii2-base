<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions\backend;

use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\BaseActiveRecord;

class IndexAction extends Action
{

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function run(): string
    {
        /* @var $modelClass BaseActiveRecord */
        $modelClass = $this->modelClass;
        $query = $modelClass::find();
        $dataProvider = Yii::createObject([
            'class' => ActiveDataProvider::class,
            'query' => $query,
        ]);
        $controller = $this->controller;

        return $controller->render($controller->action->id, [
            'dataProvider' => $dataProvider,
        ]);
    }
}
