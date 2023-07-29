<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions\backend;

use davidxu\base\helpers\ActionHelper;
use Throwable;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\db\StaleObjectException;

class DeleteAction extends Action
{
    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function run()
    {
        $controller = $this->controller;
        $id = Yii::$app->request->get('id');
        /** @var Model|ActiveRecordInterface|ActiveRecord $model */
        $model = $this->findModel($id);
        return $model->delete()
            ? ActionHelper::message(Yii::t('base', 'Deleted successfully'),
                $controller->redirect(Yii::$app->request->referrer))
            : ActionHelper::message(ActionHelper::getError($model),
                $controller->redirect(Yii::$app->request->referrer), 'error');
    }
}
