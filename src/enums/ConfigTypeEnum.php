<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\enums;

use Yii;

/**
 * Class ConfigTypeEnum
 * @package davidxu\base\enums
 */
class ConfigTypeEnum extends BaseEnum
{
    public const CONFIG_TEXT = 'text';
    public const CONFIG_PASSWORD = 'password';
    public const CONFIG_SECRETKEY_TEXT = 'secretKeyText';
    public const CONFIG_TEXTAREA = 'textarea';
    public const CONFIG_DATE = 'date';
    public const CONFIG_TIME = 'time';
    public const CONFIG_DATETIME = 'datetime';
    public const CONFIG_DROPDOWN_LIST = 'dropDownList';
    public const CONFIG_MULTI_INPUT = 'multipleInput';
    public const CONFIG_RADIO = 'radioList';
    public const CONFIG_CHECKBOX = 'checkboxList';
    public const CONFIG_EDITOR = 'editor';
    public const CONFIG_IMAGE = 'image';
    public const CONFIG_IMAGES = 'images';
    public const CONFIG_FILE = 'file';
    public const CONFIG_FILES = 'files';
    public const CONFIG_CROPPER = 'cropper';
    public const CONFIG_LAT_LNG = 'latLngSelection';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::CONFIG_TEXT => Yii::t('base', 'Text'),
            self::CONFIG_PASSWORD => Yii::t('base', 'Password'),
            self::CONFIG_SECRETKEY_TEXT => Yii::t('base', 'SecretKeyText'),
            self::CONFIG_TEXTAREA => Yii::t('base', 'Textarea'),
            self::CONFIG_DATE => Yii::t('base', 'Date'),
            self::CONFIG_TIME => Yii::t('base', 'Time'),
            self::CONFIG_DATETIME => Yii::t('base', 'DateTime'),
            self::CONFIG_DROPDOWN_LIST => Yii::t('base', 'DropDownList'),
            self::CONFIG_MULTI_INPUT => Yii::t('base', 'MultipleInput'),
            self::CONFIG_RADIO => Yii::t('base', 'RadioList'),
            self::CONFIG_CHECKBOX => Yii::t('base', 'CheckboxList'),
            self::CONFIG_EDITOR => Yii::t('base', 'Editor'),
            self::CONFIG_IMAGE => Yii::t('base', 'Image'),
            self::CONFIG_IMAGES => Yii::t('base', 'Images'),
            self::CONFIG_FILE => Yii::t('base', 'File'),
            self::CONFIG_FILES => Yii::t('base', 'Files'),
            self::CONFIG_CROPPER => Yii::t('base', 'Cropper'),
        ];
    }

    public static function hasAttachment(): array
    {
        return [
            self::CONFIG_IMAGE,
            self::CONFIG_IMAGES,
            self::CONFIG_FILE,
            self::CONFIG_FILES,
            self::CONFIG_CROPPER,
        ];
    }
}
