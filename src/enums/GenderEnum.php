<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\enums;

use Yii;

/**
 * Gender Enum
 *
 * Class GenderEnum
 * @package davidxu\base\enums
 * @author David Xu <david.xu.uts@163.com>
 */
class GenderEnum extends BaseEnum
{
    public const UNKNOWN = 0;
    public const MALE = 1;
    public const FEMALE = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MALE => Yii::t('configtr', 'Male'),
            self::FEMALE => Yii::t('configtr', 'Female'),
            self::UNKNOWN => Yii::t('configtr', 'Unknown'),
        ];
    }
}
