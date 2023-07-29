<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\actions;

use davidxu\base\enums\AttachmentTypeEnum;
use davidxu\base\enums\UploadTypeEnum;
use davidxu\base\models\Attachment;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use Qiniu\Etag;
use yii\base\Action;
use Yii;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\imagine\Image;
use yii\web\Response;
use yii\i18n\PhpMessageSource;
use FFMpeg\FFProbe;
use yii\web\UploadedFile;
use yii\base\Exception;

class BaseAction extends Action
{
    /** @var string */
    public string $url;

    /** @var string */
    public string $fileDir;

    /** @var bool */
    public bool $allowAnony = false;

    /** @var ActiveRecord|string|ActiveRecordInterface  */
    public ActiveRecord|string|ActiveRecordInterface $attachmentModel = Attachment::class;

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

    /**
     * @param array|object $params
     * @return array|true[]
     */
    protected function getHash(array|object $params): array
    {
        /** @var Attachment|ActiveRecordInterface|ActiveRecord $model */
        $model = $this->attachmentModel::findOne(['hash' => $params['hash']]);
        if ($model) {
            if (!($model->poster)) {
                $model->poster = '/images/default-video.jpg';
            }
//            $model->path = $this->url . $model->path;
            $result = [
                'success' => true,
                'response' => $model,
                'data' => $model,
            ];
        } else {
            $result = [
                'error' => true,
            ];
        }
        return $result;
    }

    /**
     * @param UploadedFile|null $file
     * @param object|array $params
     * @param string $url
     * @param string $dir
     * @return array|bool|bool[]
     * @throws Exception
     */
    protected function local(?UploadedFile $file, object|array $params, string $url, string $dir): array|bool
    {
        return $this->processChunk($file, $params, $url, $dir);
    }

    /**
     * @param array|object $params
     * @return array
     */
    protected function qiniu(array|object $params): array
    {
        if ($params['store_in_db'] === true || $params['store_in_db'] === 'true') {
            /** @var Attachment|ActiveRecordInterface|ActiveRecord $model */
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
                    'response' => $model,
                    'data' => $model,
                ];
            } else {
                $msg = YII_ENV_PROD
                    ? Yii::t('base', 'Data writing error')
                    : array_values($model->getFirstErrors())[0];
                $result = [
                    'error' => true,
                    'response' => $msg,
                    'data' => $msg,
                ];
            }
        } else {
            $params['path'] = $this->url . $params['path'];
            $result = [
                'success' => true,
                'response' => $params,
                'data' => $params,
            ];
        }
        return $result;
    }

    /**
     * @param UploadedFile|null $file
     * @param array|object $params
     * @param string $url
     * @param string $dir
     * @return array
     * @throws Exception
     */
    private function processChunk(?UploadedFile $file, array|object $params, string $url, string $dir): array
    {
        $chunksStorePath = Yii::getAlias('@runtime/chunks');
        if (!is_dir($chunksStorePath)) {
            @mkdir($chunksStorePath, 0755, true);
        }
        if (isset($params['eof']) && ($params['eof'] === true || $params['eof'] === 'true')) {
            if (str_ends_with($url, '/')) {
                $url = rtrim($url);
            }
            if (str_ends_with($dir, '/')) {
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

            if ($this->mergeFile($chunksStorePath, $params, $savePath)) {
                $result = $this->getInfo($savePath, $urlPath, $params);
            } else {
                $result = [
                    'error' => true,
                    'completed' => false,
                    'response' => Yii::t('base', 'Data writing error'),
                ];
            }
        } else {
            if (!($file->saveAs($chunksStorePath . DIRECTORY_SEPARATOR
                . $params['chunk_key'] . '_' . $params['chunk_index']))
            ) {
                $result = [
                    'error' => true,
                    'completed' => false,
                    'response' => Yii::t('base', 'Data writing error'),
                    'data' => Yii::t('base', 'Data writing error'),
                ];
            } else {
                $result = [
                    'success' => true,
                    'completed' => true,
                    'data' => [
                        'key' => $params['key'],
                        'extension' => $params['extension'],
                        'file_type' => $params['file_type'],
                        'chunk_key' => $params['chunk_key'],
                        'total_chunks' => $params['total_chunks'],
                    ],
                    'response' => [
                        'key' => $params['key'],
                        'extension' => $params['extension'],
                        'file_type' => $params['file_type'],
                        'chunk_key' => $params['chunk_key'],
                        'total_chunks' => $params['total_chunks'],
                    ],
                ];
            }
        }
        return $result;
    }

    /**
     * @param string $savePath
     * @param string $urlPath
     * @param array|object $params
     * @return array
     */
    private function getInfo(string $savePath, string $urlPath, array|object $params): array
    {
        $width = $height = 0;
        $duration = $poster = null;
        if (isset($params['file_type'])) {
            if ($params['file_type'] === AttachmentTypeEnum::TYPE_IMAGE) {
                [$width, $height] =  getimagesize($savePath);
            }
            if ($params['file_type'] === 'videos' || $params['file_type'] === 'audios') {
                [$duration, $poster, $hasPoster] = $this->getDuration($savePath, $params['extension']);
                $poster = $hasPoster ? str_replace($params['extension'], '', $savePath) . '.jpg' : $poster;
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

        if (isset($params['store_in_db']) && (true === $params['store_in_db'] || $params['store_in_db'] === 'true')) {
            $result = $this->saveToDB($info);
        } else {
            if ($cache = Yii::$app->cache) {
                $cache->set($info['path'], $info, null, new TagDependency(['tags' => $info['path'] . $info['hash']]));
            }
            $result = [
                'success' => true,
                'completed' => true,
                'data' => $info,
                'response' => $info,
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
    private function mergeFile(string $storePath, array|object $params, string $savePath): bool
    {
        $chunks = [];
        for ($i = 0; $i < $params['total_chunks']; $i++) {
            $chunkFile = $storePath . DIRECTORY_SEPARATOR . $params['chunk_key'] . '_' . $i;
            if(file_exists($chunkFile) && filesize($chunkFile) > 0) {
                $chunks[$i] = $chunkFile;
//                $chunks[$i] = $params['chunk_key'] . '_' . $i;
            } else {
                break;
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
     * @param array|object $info
     * @return array
     */
    private function saveToDB(array|object $info): array
    {
        /** @var ActiveRecord|ActiveRecordInterface|object|Attachment|array $model */
        if (isset($info['hash'])) {
            $model = $this->attachmentModel::findOne(['hash' => $info['hash']]);
            if ($model) {
                if (!($model->poster) || $model->poster === '') {
                    $model->poster = '/images/default-video.jpg';
                }
                $model->path = $this->url . $model->path;
                $result = [
                    'success' => true,
                    'data' => $model,
                    'response' => $model,
                ];
            } else {
                $result = $this->saveNewRecord($info);
            }
        } else {
            $result = $this->saveNewRecord($info);
        }
        return $result;
    }

    /**
     * @param array|object $info
     * @return array
     */
    private function saveNewRecord(array|object $info): array
    {
        $model = new $this->attachmentModel;
        $model->attributes = $info;
        if ($model->save()) {
            $model->refresh();
            $result = [
                'success' => true,
                'data' => $model,
                'response' => $model,
            ];
        } else {
            $msg = YII_ENV_PROD
                ? Yii::t('base', 'Data writing error')
                : array_values($model->getFirstErrors())[0];
            $result = [
                'error' => true,
                'data' => $msg,
                'response' => $msg,
            ];
        }
        return $result;
    }

    /**
     * @param string $file
     * @param string $extension
     * @return array
     */
    private function getDuration(string $file, string $extension): array
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
            Image::thumbnail($poster, 400, 0)->save($poster);
            return [
                $duration,
                $poster,
                true
            ];
        } catch (\Exception) {
            return [
                null,
                '/images/default-video.jpg',
                false
            ];
        }
    }

    /**
     * @return void
     */
    protected function registerTranslations(): void
    {
        $i18n = Yii::$app->i18n;
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
