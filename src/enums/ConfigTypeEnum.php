<?php

namespace davidxu\base\enums;

use Yii;

/**
 * Class ConfigTypeEnum
 * @package davidxu\base\enums
 */
class ConfigTypeEnum extends BaseEnum
{
    public const CONFIG_TEXT = 'text';
    public const CONFIT_PASSWORD = 'password';
    public const CONFIT_SECRETKEY_TEXT = 'secretKeyText';
    public const CONFIT_TEXTAREA = 'textarea';
    public const CONFIT_DATE = 'date';
    public const CONFIT_TIME = 'time';
    public const CONFIT_DATETIME = 'datetime';
    public const CONFIT_DROPDOWN_LIST = 'dropDownList';
    public const CONFIT_MULTI_INPUT = 'multipleInput';
    public const CONFIT_RADIO = 'radioList';
    public const CONFIT_CHECKBOX = 'checkboxList';
    public const CONFIT_UEDITOR = 'baiduUEditor';
    public const CONFIT_IMAGE = 'image';
    public const CONFIT_IMAGES = 'images';
    public const CONFIT_FILE = 'file';
    public const CONFIT_FILES = 'files';
    public const CONFIT_CROPPER = 'cropper';
    public const CONFIT_LAT_LNG = 'latLngSelection';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::CONFIG_TEXT => Yii::t('configtr', 'Text'),
            self::CONFIT_PASSWORD => Yii::t('configtr', 'Password'),
            self::CONFIT_SECRETKEY_TEXT => Yii::t('configtr', 'SecretKeyText'),
            self::CONFIT_TEXTAREA => Yii::t('configtr', 'Textarea'),
            self::CONFIT_DATE => Yii::t('configtr', 'Date'),
            self::CONFIT_TIME => Yii::t('configtr', 'Time'),
            self::CONFIT_DATETIME => Yii::t('configtr', 'DateTime'),
            self::CONFIT_DROPDOWN_LIST => Yii::t('configtr', 'DropDownList'),
            self::CONFIT_MULTI_INPUT => Yii::t('configtr', 'MultipleInput'),
            self::CONFIT_RADIO => Yii::t('configtr', 'RadioList'),
            self::CONFIT_CHECKBOX => Yii::t('configtr', 'CheckboxList'),
            self::CONFIT_UEDITOR => Yii::t('configtr', 'UEditor'),
            self::CONFIT_IMAGE => Yii::t('configtr', 'Image'),
            self::CONFIT_IMAGES => Yii::t('configtr', 'Images'),
            self::CONFIT_FILE => Yii::t('configtr', 'File'),
            self::CONFIT_FILES => Yii::t('configtr', 'Files'),
            self::CONFIT_CROPPER => Yii::t('configtr', 'Cropper'),
            self::CONFIT_LAT_LNG => Yii::t('configtr', 'LatLngSelection'),
        ];
    }
}
