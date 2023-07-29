<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions;

use Yii;
use yii\helpers\Url;
use yii\base\Exception;
use yii\web\Response;
use yii\web\UploadedFile;

class LocalAction extends BaseAction
{
    /**
     * @return array
     * @throws Exception
     */
    public function run(): array
    {
        if (empty($this->url)) {
            $this->url = Yii::getAlias('@web');
        }
        if (empty($this->fileDir)) {
            $this->fileDir = Url::to('@webroot');
        }
        $post = Yii::$app->request->post();
        $file = UploadedFile::getInstanceByName('file');

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->local($file, $post, $this->url, $this->fileDir);
    }
}
