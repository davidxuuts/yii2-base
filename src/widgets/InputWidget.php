<?php

namespace davidxu\base\widgets;

use davidxu\base\enums\QiniuUploadRegionEnum;
use davidxu\base\enums\UploadTypeEnum;
use davidxu\base\helpers\StringHelper;
use Qiniu\Auth;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\i18n\PhpMessageSource;
use Yii;

/**
 * Input widget
 * @property array $clientOptions
 * @property array $metaData
 * @property string $url
 * @property string $uploadBasePath
 * @property string $drive
 * @property bool $storeInDB
 * @property int|string $chunkSize
 * @property string $qiniuBucket
 * @property string $qiniuAccessKey
 * @property string $qiniuSecretKey
 * @property string $qiniuCallbackUrl
 * @property array $qiniuCallbackBody
 */
class InputWidget extends \yii\bootstrap4\InputWidget
{
    public $clientOptions = [];
    public $metaData = [];
    public $url;
    public $getHashUrl;
    public $uploadBasePath = 'uploads/';
    public $drive = UploadTypeEnum::DRIVE_LOCAL;
    public $storeInDB = true;
    public $chunkSize = 5 * 1024 * 1024;
    public $secondUpload = false;

    // Qiniu
    public $qiniuBucket;
    public $qiniuAccessKey;
    public $qiniuSecretKey;
    public $qiniuCallbackUrl;
    public $qiniuCallbackBody = [
        'drive' => UploadTypeEnum::DRIVE_QINIU,
        'specific_type' => '$(mimeType)',
        'file_type' => '$(x:file_type)',
        'path' => '$(key)',
        'hash' => '$(etag)',
        'size' => '$(fsize)',
        'name' => '$(fname)',
        'extension' => '$(ext)',
        'member_id' => '$(x:member_id)',
        'width' => '$(imageInfo.width)',
        'height' => '$(imageInfo.height)',
        'duration' => '$(avinfo.format.duration)',
        'store_in_db' => '$(x:store_in_db)',
        'upload_ip' => '$(x:upload_ip)',
    ];

    public function init()
    {
        $this->registerTranslations();
        $_view = Yii::$app->getView();

        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }

        if (empty($this->name) && (!empty($this->model) && !empty($this->attribute))) {
            $this->name = Html::getInputName($this->model, $this->attribute);
        }

        // second upload function
        if ($this->secondUpload) {
            if (empty($this->getHashUrl) || $this->getHashUrl === '' ) {
                throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
                    'attribute' => 'getHashUrl',
                ]));
            }
        }

        // Local
        if ($this->drive === UploadTypeEnum::DRIVE_LOCAL) {
            if (empty($this->url) || $this->url === '' ) {
                throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
                    'attribute' => 'url',
                ]));
            }
        }

        // Qiniu
        if ($this->drive === UploadTypeEnum::DRIVE_QINIU) {
            if (empty($this->qiniuCallbackUrl) || $this->qiniuCallbackUrl === '') {
                if (!isset(Yii::$app->params['qiniu.callbackUrl']) || Yii::$app->params['qiniu.callbackUrl'] === '') {
                    throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
                        'attribute' => 'qiniu.callbackUrl',
                    ]));
                }
                $this->qiniuCallbackUrl = Yii::$app->params['qiniu.callbackUrl'];
            }

            if (empty($this->qiniuBucket) || $this->qiniuBucket === '') {
                if (!isset(Yii::$app->params['qiniu.bucket']) || Yii::$app->params['qiniu.bucket'] === '') {
                    throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
                        'attribute' => 'qiniu.bucket',
                    ]));
                }
                $this->qiniuBucket = Yii::$app->params['qiniu.bucket'];
            }

            if (empty($this->qiniuAccessKey) || $this->qiniuAccessKey === '') {
                if (!isset(Yii::$app->params['qiniu.accessKey']) || Yii::$app->params['qiniu.accessKey'] === '') {
                    throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
                        'attribute' => 'qiniu.accessKey',
                    ]));
                }
                $this->qiniuAccessKey = Yii::$app->params['qiniu.accessKey'];
            }

            if (empty($this->qiniuSecretKey) || $this->qiniuSecretKey === '') {
                if (!isset(Yii::$app->params['qiniu.secretKey']) || Yii::$app->params['qiniu.secretKey'] === '') {
                    throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
                        'attribute' => 'qiniu.secretKey',
                    ]));
                }
                $this->qiniuSecretKey = Yii::$app->params['qiniu.secretKey'];
            }
//            if (!in_array($this->url, QiniuUploadRegionEnum::getMap())) {
//                throw new InvalidConfigException(Yii::t('base', 'Invalid configuration: {attribute}', [
//                    'attribute' => 'URL',
//                ]));
//            }
        }

        $systemMaxFileSize = StringHelper::getSizeInByte(get_cfg_var('upload_max_filesize'));
        $this->chunkSize = StringHelper::getSizeInByte($this->chunkSize);
        if ($this->chunkSize > $systemMaxFileSize) {
            $this->chunkSize = $systemMaxFileSize;
        }
        parent::init();
    }

    protected function getQiniuToken()
    {
        $auth = new Auth($this->qiniuAccessKey, $this->qiniuSecretKey);
        $policy = [
            'callbackUrl' => $this->qiniuCallbackUrl,
            'callbackBody' => Json::encode($this->qiniuCallbackBody),
            'callbackBodyType' => 'application/json',
        ];
        return $auth->uploadToken($this->qiniuBucket, null, 3600, $policy);
    }

    protected function isLocalDrive()
    {
        return $this->drive === UploadTypeEnum::DRIVE_LOCAL ? 'true' : 'false';
    }

    protected function isQiniuDrive()
    {
        return $this->drive === UploadTypeEnum::DRIVE_QINIU ? 'true' : 'false';
    }

    protected function isCosDrive()
    {
        return $this->drive === UploadTypeEnum::DRIVE_COS ? 'true' : 'false';
    }

    protected function isOssDrive()
    {
        return $this->drive === UploadTypeEnum::DRIVE_OSS ? 'true' : 'false';
    }

    protected function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['base*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en-US',
            'basePath' => Yii::getAlias('@davidxu/base/messages'),
            'fileMap' => [
                'dropzone' => 'base.php',
            ],
        ];
    }
}
