<?php

namespace davidxu\base\enums;
use Yii;

/**
 * AttachmentType Enum
 *
 * Class AttachmentTypeEnum
 * @package davidxu\base\enums
 * @author David Xu <david.xu.uts@163.com>
 */
class AttachmentTypeEnum extends BaseEnum
{
    public const TYPE_IMAGES = 'images';
    public const TYPE_VIDEOS = 'videos';
    public const TYPE_AUDIOS = 'audios';
    public const TYPE_NEWS   = 'news';
    public const TYPE_THUMBNAILS = 'thumbs';
    public const TYPE_OTHERS = 'others';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::TYPE_IMAGES => Yii::t('base', 'Images'),
            self::TYPE_VIDEOS => Yii::t('base', 'Videos'),
            self::TYPE_AUDIOS => Yii::t('base', 'Audios'),
            self::TYPE_NEWS => Yii::t('base', 'Hybrids'),
            self::TYPE_THUMBNAILS => Yii::t('base', 'Thumbnails'),
            self::TYPE_OTHERS => Yii::t('base', 'Others'),
        ];
    }
}
