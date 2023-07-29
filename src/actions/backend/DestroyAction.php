<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions\backend;

use davidxu\base\enums\StatusEnum;
use davidxu\base\helpers\ActionHelper;
use Yii;
use yii\db\BaseActiveRecord;

class DestroyAction extends Action
{
    public function run()
    {
        $controller = $this->controller;
        /* @var $modelClass BaseActiveRecord */
        $modelClass = $this->modelClass;
        $id = Yii::$app->request->get('id');
        if (!($model = $modelClass::findOne($id))) {
            return ActionHelper::message(
                Yii::t('base', 'Data not found'),
                $controller->redirect(Yii::$app->request->referrer),
                'error'
            );
        }
        if (isset($model->status)) {
            $model->status = StatusEnum::DELETE;
        }
        return $model->save()
            ? ActionHelper::message(Yii::t('base', 'Deleted successfully'),
                $controller->redirect(Yii::$app->request->referrer))
            : ActionHelper::message(ActionHelper::getError($model),
                $controller->redirect(Yii::$app->request->referrer), 'error');
    }
}
