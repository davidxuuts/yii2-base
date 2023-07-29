<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

namespace davidxu\base\enums;

use Yii;

/**
 * Merchant Status Enum
 *
 * Class MerchantStatusEnum
 * @package davidxu\base\enums
 */
class MerchantStatusEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const AUDIT = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => Yii::t('app', 'Enabled'),
            self::DISABLED => Yii::t('app', 'Disabled'),
            self::AUDIT => Yii::t('app', 'Auditing'),
        ];
    }
}