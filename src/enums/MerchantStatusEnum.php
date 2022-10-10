<?php

namespace davidxu\base\enums;

use davidxu\base\enums\BaseEnum;
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