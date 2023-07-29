<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions\backend;

use davidxu\base\helpers\ActionHelper;
use Yii;

class EditAction extends Action
{
    public function run()
    {
        $controller = $this->controller;
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? ActionHelper::message(Yii::t('base', 'Saved successfully'),
                    $controller->redirect(Yii::$app->request->referrer))
                : ActionHelper::message(ActionHelper::getError($model),
                    $controller->redirect(Yii::$app->request->referrer),
                    'error'
                );
        }

        return $controller->render($controller->action->id, [
            'model' => $model,
        ]);
    }
}
