<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions\backend;

use davidxu\base\helpers\ActionHelper;
use davidxu\config\helpers\ResponseHelper;
use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

class SortOrderAction extends Action
{

    public function run()
    {
        /* @var $modelClass BaseActiveRecord */
        $modelClass = $this->modelClass;
        $id = Yii::$app->request->get('id');
        if (!($model = $modelClass::findOne($id))) {
            return ResponseHelper::json(404, Yii::t('base', 'Data not found'));
        }

        $model->attributes = ArrayHelper::filter(Yii::$app->request->get(), ['order', 'status']);
        if (!$model->save()) {
            return ResponseHelper::json(422, ActionHelper::getError($model));
        }
        return ResponseHelper::json(200, Yii::t('base', 'Saved successfully'), $model->attributes);

    }
}
