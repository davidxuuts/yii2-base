<?php

namespace davidxu\base\actions;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\JsonParser;
use yii\web\Response;

class QiniuAction extends BaseAction
{
    /**
     * @return void
     * @throws BadRequestHttpException
     */
    public function run()
    {
        $this->allowAnony = true;
        Yii::$app->request->parsers['application/json'] = JsonParser::class;
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->qiniu(Yii::$app->request->post());
    }
}
