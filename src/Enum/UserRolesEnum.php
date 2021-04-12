<?php

namespace App\Enum;

class UserRolesEnum
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_CMS = 'ROLE_CMS';
    public const ROLE_MANAGER = 'ROLE_MANAGER';
    public const ROLE_ADMIN = 'ROLE_ADMIN';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    public static function getEnumArray(): array
    {
        return array_flip(self::getReversedEnumArray());
    }

    public static function getReversedEnumArray(): array
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
