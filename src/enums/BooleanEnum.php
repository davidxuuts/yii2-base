<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\enums;

use Yii;

/**
 * Boolean Enum
 *
 * Class Boolean
 * @package davidxu\base\enums
 * @author David Xu <david.xu.uts@163.com>
 */
class BooleanEnum extends BaseEnum
{

    const YES = 1;
    const NO = 0;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::YES => Yii::t('yii', 'Yes'),
            self::NO => Yii::t('yii', 'No'),
        ];
    }
}
