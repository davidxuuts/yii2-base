<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\helpers;

use Yii;
use yii\base\ExitException;
use yii\base\Model;
use yii\bootstrap4\ActiveForm;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\web\Response;

class ActionHelper
{
    const MESSAGE_SUCCESS   = 'success';
    const MESSAGE_ERROR     = 'error';
    const MESSAGE_INFO      = 'info';
    const MESSAGE_WARNING   = 'warning';

    /**
     * Alert or toast
     *
     * @param mixed $msg Message
     * @param string $type Message type [success/error/info/warning]
     * @return void
     */
    public static function toast(mixed $msg, string $type = self::MESSAGE_SUCCESS): void
    {
        if (!in_array($type, [self::MESSAGE_SUCCESS, self::MESSAGE_ERROR, self::MESSAGE_INFO, self::MESSAGE_WARNING])) {
            $type = self::MESSAGE_SUCCESS;
        }
        Yii::$app->session->setFlash($type, $msg, false);
    }

    /**
     * Message redirect
     *
     * @param mixed $msg Message
     * @param mixed $redirectUrl Redirect URL
     * @param string $type Message type [success/error/info/warning]
     * @return mixed
     */
    public static function message(mixed $msg, mixed $redirectUrl, string $type = self::MESSAGE_SUCCESS): mixed
    {
        if (!in_array($type, [self::MESSAGE_SUCCESS, self::MESSAGE_ERROR, self::MESSAGE_INFO, self::MESSAGE_WARNING])) {
            $type = self::MESSAGE_SUCCESS;
        }
        Yii::$app->session->setFlash($type, $msg);
        return $redirectUrl;
    }

    /**
     * @param Model|ActiveRecordInterface $model
     * @return string
     */
    public static function getError(Model|ActiveRecordInterface $model): string
    {
        return self::analysisErrors($model->getFirstErrors());
    }

    /**
     * Analysis Errors
     *
     * @param array|string $errors
     * @return bool|string
     */
    public static function analysisErrors(array|string $errors): bool|string
    {
        if (!is_array($errors) || empty($errors)) {
            return false;
        }
        $firstErrors = array_values($errors)[0];
        return $firstErrors ?? Yii::t('base', 'Error message not fount');
    }

    /**
     * @param Model|ActiveRecord|ActiveRecordInterface $model
     * @return void
     * @throws ExitException
     */
    public static function activeFormValidate(Model|ActiveRecord|ActiveRecordInterface $model): void
    {
        if (Yii::$app->request->isAjax && !Yii::$app->request->isPjax) {
            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->data = ActiveForm::validate($model);
                Yii::$app->end();
            }
        }
    }
}
