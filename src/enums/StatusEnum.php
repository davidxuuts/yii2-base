<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\enums;

use Yii;

/**
 * Status Enum
 *
 * Class Status
 * @package davidxu\base\enums
 * @author David Xu <david.xu.uts@163.com>
 */
class StatusEnum extends BaseEnum
{

    const ENABLED = 1;
    const DISABLED = 0;
    const DELETE = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => Yii::t('configtr', 'Enabled'),
            self::DISABLED => Yii::t('configtr', 'Disabled'),
            self::DELETE => Yii::t('configtr', 'Deleted'),
        ];
    }

    /**
     * @return array
     */
    public static function getBoolMap(): array
    {
        return [
            self::ENABLED => Yii::t('configtr', 'Enabled'),
            self::DISABLED => Yii::t('configtr', 'Disabled'),
        ];
    }

    /**
     * @return array
     */
    public static function getBoolKeys(): array
    {
        return array_keys(static::getBoolMap());
    }
}
