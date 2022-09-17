<?php

namespace davidxu\base\actions;

use davidxu\base\enums\AttachmentTypeEnum;
use davidxu\base\enums\UploadTypeEnum;
use davidxu\base\models\Attachment;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Qiniu\Etag;
use yii\base\Action;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\imagine\Image;
use yii\web\Response;
use yii\i18n\PhpMessageSource;
use FFMpeg\FFProbe;
use yii\web\UploadedFile;
use yii\base\Exception;

class BaseAction extends Action
{
    /** @var string */
    public $url;

    /** @var string */
    public $fileDir;

    /** @var bool */
    public $allowAnony = false;

//    /** @var bool  */
//    public $storeInDB = true;
    /** @var ActiveRecord  */
    public $attachmentModel = Attachment::class;

    /**
     * @return array[]|void
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        if (Yii::$app->user->isGuest && !$this->allowAnony) {
            $result = [
                'error' => [
                    'message' => Yii::t('base', 'Anonymous user is not allowed, please login first'),
                ],
            ];
            Yii::$app->response->format = Response::FORMAT_JSON;
            return $result;
        }
    }

    protected function getHash($params)
    {
        $model = $this->attachmentModel::findOne(['hash' => $params['hash']]);
        if ($model) {
            if (!$model->poster) {
                $model->poster = '/images/default-video.jpg';
            }
            $result = [
                'success' => true,
                'result' => $model,
            ];
        } else {
            $result = [
                'error' => true,
            ];
        }
        return $result;
    }

    /**
     * @param UploadedFile $file
     * @param array|object $params
     * @param string $url
     * @param string $dir
     * @return array|bool|bool[]
     * @throws Exception
     */
    protected function local($file, $params, $url, $dir)
    {
        return $this->processChunk($file, $params, $url, $dir);
    }

    /**
     * @param array $params
     * @return array
     */
    protected function qiniu($params) {
        if ($params['store_in_db'] === true || $params['store_in_db'] === 'true') {
            /** @var Attachment $model */
            $model = new $this->attachmentModel;
            $model->attributes = $params;
            $extension = explode('.', $model->extension);
            $model->extension = $extension[count($extension) - 1];
            if ($model->width === 'null') {
                $model->width = 0;
            }
            if ($model->height === 'null') {
                $model->height = 0;
            }
            if ($model->duration === 'null' || $model->duration === '') {
                $model->duration = null;
            }
            if ($model->file_type === 'videos' && $model->duration) {
                $offset = rand(0, intval($model->duration));
                $model->poster = $model->path . '?vframe/jpg/offset/' . $offset . '/w/480/h/360';
            }
            $model->year = date('Y');
            $model->month = date('m');
            $model->day = date('d');
            if ($model->save()) {
                $model->refresh();
                $model->path = $this->url . $model->path;
                $model->poster = $model->poster ? $this->url . $model->path : null;
                $result = [
                    'success' => true,
                    'result' => $model,
                ];
            } else {
                $msg = YII_ENV_PROD
                    ? Yii::t('dropzone', 'Data writting error')
                    : array_values($model->getFirstErrors())[0];
                $result = [
                    'error' => true,
                    'result' => $msg,
                ];
            }
        } else {
            $params['path'] = $this->url . $params['path'];
            $result = [
                'success' => true,
                'result' => $params,
            ];
        }
//        $result['url'] = $this->url;
        return $result;
    }

    /**
     * @param UploadedFile $file
     * @param array|object $params
     * @param string $url
     * @param string $dir
     * @return bool
     * @throws Exception
     */
    private function processChunk($file, $params, $url, $dir)
    {
        $chunsStorePath = Yii::getAlias('@runtime/chunks');
        if (!is_dir($chunsStorePath)) {
            @mkdir($chunsStorePath, 0755, true);
        }
        if (isset($params['eof']) && ($params['eof'] || $params['eof'] === 'true')) {
            if (substr($url, -1) === '/') {
                $url = rtrim($url);
            }
            if (substr($dir, -1) === '/') {
                $dir = rtrim($dir);
            }

            if ($params['key']) {
                if (substr($params['key'], 1) === '/') {
                    $key = ltrim($params['key'], 1);
                } else {
                    $key = $params['key'];
                }
                $urlPath = $url . DIRECTORY_SEPARATOR . $key;
                $savePath = $dir . DIRECTORY_SEPARATOR . $key;
            } else {
                $relativePath = DIRECTORY_SEPARATOR
                    . $params['file_type'] . DIRECTORY_SEPARATOR
                    . date('Ymd') . DIRECTORY_SEPARATOR
                    . Yii::$app->security->generateRandomString() . $params['extension'];
                $urlPath = $url . $relativePath;
                $savePath = $dir . $relativePath;
            }

            if ($this->mergeFile($chunsStorePath, $params, $savePath)) {
                $result = $this->getInfo($savePath, $urlPath, $params);
            } else {
                $result = [
                    'error' => true,
                    'result' => Yii::t('base', 'Data writting error'),
                ];
            }
        } else {
            if (!($file->saveAs($chunsStorePath . DIRECTORY_SEPARATOR
                . $params['chunk_key'] . '_' . $params['chunk_index']))
            ) {
                $result = [
                    'error' => true,
                    'result' => Yii::t('base', 'Data writting error'),
                ];
            } else {
                $result = [
                    'success' => true,
                    'result' => true,
                ];
            }
        }
        return $result;
    }

    /**
     * @param string $savePath
     * @param string $urlPath
     * @param array|Attachment $params
     * @return array
     */
    private function getInfo($savePath, $urlPath, $params)
    {
        $width = $height = 0;
        $duration = $poster = null;
        if (isset($params['file_type'])) {
            if ($params['file_type'] === 'images') {
                [$width, $height] =  getimagesize($savePath);
            }
            if ($params['file_type'] === 'videos' || $params['file_type'] === 'audios') {
                [$duration, $poster] = $this->getDuration($savePath, $params['extension']);
            }
        }

        $info = [
            'member_id' => Yii::$app->user->isGuest ? 0 : Yii::$app->user->id,
            'drive' => UploadTypeEnum::DRIVE_LOCAL,
            'specific_type' => $params['mime_type'],
            'file_type' => $params['file_type'],
            'path' => $urlPath,
            'poster' => $poster,
            'name' => $params['name'],
            'extension' => ltrim(trim($params['extension']), 1),
            'size' => $params['size'],
            'year' => date('Y'),
            'month' => date('m'),
            'day' => date('d'),
            'width' => $width,
            'height' => $height,
            'duration' => $duration,
            'hash' => Etag::sum($savePath) ? Etag::sum($savePath)[0] : null,
            'upload_ip' => Yii::$app->request->remoteIP,
        ];

        if (isset($params['store_in_db']) && ($params['store_in_db'] || $params['store_in_db'] === 'true')) {
            $result = $this->saveToDB($info);
        } else {
            $result = [
                'sucess' => true,
                'result' => $info,
            ];
        }
        return $result;
    }

    /**
     * @param string $storePath
     * @param array|object $params
     * @param string $savePath
     * @return bool
     */
    private function mergeFile($storePath, $params, $savePath)
    {
        for ($i = 0; $i < $params['total_chunks']; $i++) {
            $chunkFile = $storePath . DIRECTORY_SEPARATOR . $params['chunk_key'] . '_' . $i;
            if(file_exists($chunkFile) && filesize($chunkFile) > 0) {
                $chunks[$i] = $chunkFile;
//                $chunks[$i] = $params['chunk_key'] . '_' . $i;
            } else {
                break;
                return false;
            }
        }

        $fp = fopen($savePath, 'wb+');
        if (flock($fp, LOCK_EX)) {
            foreach ($chunks as $chunk) {
                $handle = fopen($chunk,"rb");
                if (fwrite($fp, fread($handle, filesize($chunk)))) {
                    fclose($handle);
                    unset($handle);
                    unlink($chunk);
                }
            }
        }
        if (flock($fp, LOCK_UN) && fclose($fp)) {
            unset($fp);
            return true;
        }
        return false;
    }

    /**
     * @param array $info
     * @return array
     */
    private function saveToDB($info)
    {
        /** @var ActiveRecord $model */
        $model = new $this->attachmentModel;
        $model->attributes = $info;
        if ($model->save()) {
            $model->refresh();
//                $model->path = $this->url . $model->path;
            $result = [
                'success' => true,
                'result' => $model,
            ];
        } else {
            $msg = YII_ENV_PROD
                ? Yii::t('base', 'Data writting error')
                : array_values($model->getFirstErrors())[0];
            $result = [
                'error' => true,
                'result' => $msg,
            ];
        }
        return $result;
    }

    /**
     * @param string $file
     * @param string $extension
     * @return mixed|void|null
     */
    private function getDuration($file, $extension)
    {
        try {
            $ffProbe = isset(Yii::$app->params['ffmpeg'])
            && isset(Yii::$app->params['ffmpeg']['ffmpeg.binaries'])
            && isset(Yii::$app->params['ffmpeg']['ffprobe.binaries'])
                ? FFProbe::create(Yii::$app->params['ffmpeg'])
                : FFProbe::create();
            $ffmpeg = isset(Yii::$app->params['ffmpeg'])
            && isset(Yii::$app->params['ffmpeg']['ffmpeg.binaries'])
            && isset(Yii::$app->params['ffmpeg']['ffprobe.binaries'])
                ? FFMpeg::create(Yii::$app->params['ffmpeg'])
                : FFMpeg::create();
            $duration = $ffProbe->format($file)->get('duration');

            $offset = rand(0, intval($duration));
            $video = $ffmpeg->open($file);
            $frame = $video->frame(TimeCode::fromSeconds($offset));
            $poster = str_replace($extension, '', $file) . '.jpg';
            $frame->save($poster);
            Image::thumbnail($poster, 400, null)->save($poster);
            return [
                'duration' => $duration,
                'poster' => $poster,
            ];
        } catch (\Exception $exception) {
            return [
                'duration' => null,
                'poster' => '/images/default-video.jpg',
            ];
        }
    }

    /**
     * @return void
     */
    protected function registerTranslations()
    {
        $i18n = Yii::$app->i18n;
        $i18n->translations['dropzone*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en-US',
            'basePath' => Yii::getAlias('@davidxu/dropzone/messages'),
            'fileMap' => [
                'dropzone' => 'dropzone.php',
            ],
        ];
        $i18n->translations['base*'] = [
            'class' => PhpMessageSource::class,
            'sourceLanguage' => 'en-US',
            'basePath' => Yii::getAlias('@davidxu/base/messages'),
            'fileMap' => [
                '*' => 'base.php',
            ],
        ];
    }
}
