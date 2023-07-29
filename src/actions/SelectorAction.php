<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions;

use Yii;
use yii\data\ActiveDataProvider;
use yii\base\Exception;

class SelectorAction extends BaseAction
{
    public string $view = '@davidxu/base/views/selector';
    public string $viewPartial = '@davidxu/base/views/_selector_partial';

    /**
     * @return string
     * @throws Exception
     */
    public function run(): string
    {
        $modelClass = $this->attachmentModel;
        $key = trim(Yii::$app->request->post('key', ''));
        $type = trim(Yii::$app->request->get('type', ''));
        $query = $modelClass::find();
        if ($key) {
            $query->andWhere(['like', 'name', $key]);
        }
        if ($type) {
            $query->andWhere(['like', 'file_type', $type]);
        }
        $dataProvider = Yii::createObject([
            'class' => ActiveDataProvider::class,
            'pagination' => [
                'pageSize' => 12,
            ],
            'query' => $query,
        ]);
        $this->view = $this->view ?? '@davidxu/base/views/selector';
        $this->viewPartial = $this->viewPartial ?? '@davidxu/base/views/selector';

        if (Yii::$app->request->isPost || array_key_exists('page', Yii::$app->request->getQueryParams())) {
            return $this->controller->renderPartial($this->viewPartial, [
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->controller->renderAjax($this->view, [
            'dataProvider' => $dataProvider,
        ]);
    }
}
