<?php

namespace davidxu\base\enums;

/**
 * AppId Enum
 *
 * Class AppIdEnum
 * @package davidxu\base\enums
 * @author David Xu <david.xu.uts@163.com>
 */
class AppIdEnum extends BaseEnum
{

    public const BACKEND = 0;
    public const FRONTEND = 1;
    public const API = 2;
    public const HTML5 = 3;
    public const MERCHANT = 4;

    /**
     * @inheritDoc
     */
    public static function getMap(): array
    {
        return [
            self::BACKEND => 'BACKEND',
            self::FRONTEND => 'FRONTEND',
            self::API => 'API',
            self::HTML5 => 'HTML5',
            self::MERCHANT => 'MERCHANT',
        ];
    }

    public static function api(): array
    {
        return [
            self::API,
        ];
    }
}
