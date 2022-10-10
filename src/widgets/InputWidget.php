<?php

namespace davidxu\base\widgets;

use davidxu\base\enums\QiniuUploadRegionEnum;
use davidxu\base\enums\UploadTypeEnum;
use davidxu\base\helpers\StringHelper;
use davidxu\config\helpers\ArrayHelper;
use Qiniu\Auth;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\i18n\PhpMessageSource;
use yii\bootstrap4\InputWidget as BS4InputWidget;
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
class InputWidget extends BS4InputWidget
{
    public $lang;
    public $clientOptions = [];
    public $metaData = [];
    public $url;
    public $getHashUrl;
    public $uploadBasePath = 'uploads/';
    public $drive = UploadTypeEnum::DRIVE_LOCAL;
    public $storeInDB = true;
    public $chunkSize = 4 * 1024 * 1024;
    public $secondUpload = false;
    public $maxFiles = 1;
    public $acceptedFiles;
    public $existFiles = [];

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

    protected $_secondUpload;
    protected $_encodedExistFiles;
    protected $_storeInDB;
    protected $_encodedMetaData;

    public function init()
    {
        parent::init();
        $this->lang = $this->lang ?? Yii::$app->language;
        $this->registerTranslations();
        $_view = Yii::$app->getView();
        if ($this->name === null && !$this->hasModel()) {
            throw new InvalidConfigException("Either 'name', or 'model' and 'attribute' properties must be specified.");
        }

        if (empty($this->name) && (!empty($this->model) && !empty($this->attribute))) {
            $this->name = Html::getInputName($this->model, $this->attribute);
        }
        $this->maxFiles = $this->maxFiles <= 0 ? 1 : $this->maxFiles;

        // second upload function
        $this->_secondUpload = $this->secondUpload ? 'true' : 'false';
        $this->_storeInDB = $this->storeInDB ? 'true' : 'false';
        $this->_encodedExistFiles = Json::encode($this->existFiles);
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
        }

        if ($this->drive === UploadTypeEnum::DRIVE_LOCAL) {
            $this->metaData['file_field'] = $this->name;
            $this->metaData['store_in_db'] = $this->storeInDB;
            if (Yii::$app->request->enableCsrfValidation) {
                $this->metaData[Yii::$app->request->csrfParam] = Yii::$app->request->getCsrfToken();
            }
        }
        if ($this->drive === UploadTypeEnum::DRIVE_QINIU) {
            $this->metaData = ArrayHelper::merge([
                'x:store_in_db' => $this->storeInDB,
                'x:member_id' => Yii::$app->user->isGuest ? 0 : Yii::$app->user->id,
                'x:upload_ip' => Yii::$app->request->remoteIP,
            ], $this->metaData);
        }

        $systemMaxFileSize = StringHelper::getSizeInByte(get_cfg_var('upload_max_filesize'));
        $this->chunkSize = StringHelper::getSizeInByte($this->chunkSize);
        if ($this->chunkSize > $systemMaxFileSize) {
            $this->chunkSize = $systemMaxFileSize;
        }
    }

    protected function getQiniuToken()
    {
        if ($this->drive === UploadTypeEnum::DRIVE_QINIU) {
            $auth = new Auth($this->qiniuAccessKey, $this->qiniuSecretKey);
            $policy = [
                'callbackUrl' => $this->qiniuCallbackUrl,
                'callbackBody' => Json::encode($this->qiniuCallbackBody),
                'callbackBodyType' => 'application/json',
            ];
            return $auth->uploadToken($this->qiniuBucket, null, 3600, $policy);
        }
        return null;
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
