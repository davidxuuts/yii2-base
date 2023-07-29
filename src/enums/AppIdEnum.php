<?php
/*
 * Copyright (c) 2023.
 * @author David Xu <david.xu.uts@163.com>
 * All rights reserved.
 */

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

    const BACKEND = 'backend';
    const FRONTEND = 'frontend';
    const API = 'api';
    const HTML5 = 'html5';
    const MERCHANT = 'merchant';
    const CONSOLE = 'console';

    /**
     * @inheritDoc
     */
    public static function getMap(): array
    {
        return [
            self::BACKEND => 'Backend',
            self::FRONTEND => 'Frontend',
            self::API => 'Api',
            self::HTML5 => 'Html5',
            self::MERCHANT => 'Merchant',
            self::CONSOLE => 'Console',
        ];
    }

    public static function getManagement(): array
    {
        return [
            self::BACKEND => 'Backend',
            self::MERCHANT => 'Merchant',
        ];
    }

    public static function api(): array
    {
        return [
            self::API,
        ];
    }
}
