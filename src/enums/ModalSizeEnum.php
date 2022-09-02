<?php

namespace davidxu\base\enums;

/**
 * ModalSize Enum
 *
 * Class AppIdEnum
 * @package davidxu\base\enums
 * @author David Xu <david.xu.uts@163.com>
 */
class ModalSizeEnum extends BaseEnum
{
    const SIZE_SMALL = 'modal-sm';
    const SIZE_LARGE = 'modal-lg';
    const SIZE_EXTRA_LARGE = 'modal-xl';

    /**
     * @inheritDoc
     */
    public static function getMap(): array
    {
        return [
            self::SIZE_SMALL => 'Small',
            self::SIZE_LARGE => 'Large',
            self::SIZE_EXTRA_LARGE => 'Extra Large',
        ];
    }
}
