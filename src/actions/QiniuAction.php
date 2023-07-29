<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions;

use Yii;
use yii\web\JsonParser;
use yii\web\Response;

class QiniuAction extends BaseAction
{

    /**
     * @return array
     */
    public function run(): array
    {
        $this->allowAnony = true;
        Yii::$app->request->parsers['application/json'] = JsonParser::class;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->qiniu(Yii::$app->request->post());
    }
}
