<?php

namespace davidxu\base\actions;

use Yii;
use yii\web\JsonParser;
use yii\web\Response;

class QiniuAction extends BaseAction
{
    /**
     * @return true[]
     */
    public function run(): array
    {
        $this->allowAnony = true;
        Yii::$app->request->parsers['application/json'] = JsonParser::class;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->qiniuInfo(Yii::$app->request->post());
    }
}
