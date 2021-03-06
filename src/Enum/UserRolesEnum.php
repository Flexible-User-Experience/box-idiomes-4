<?php

namespace App\Enum;

/**
 * UserRolesEnum class.
 *
 * @category Enum
 */
class UserRolesEnum
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_CMS = 'ROLE_CMS';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    /**
     * @return array
     */
    public static function getEnumArray()
    {
        return array_flip(self::getReversedEnumArray());
    }

    /**
     * @return array
     */
    public static function getReversedEnumArray()
    {
        return [
            self::ROLE_USER => 'backend.admin.user.user',
            self::ROLE_CMS => 'backend.admin.user.editor',
            self::ROLE_MANAGER => 'backend.admin.user.manager',
            self::ROLE_ADMIN => 'backend.admin.user.admin',
            self::ROLE_SUPER_ADMIN => 'backend.admin.user.superadmin',
        ];
    }
}
