<?php

namespace App\Http\Permissions;

class RolePermission
{
    public static function getMinimumAccessLevel() {

        return [

            '1' => [
                'auth-logoutMe',
                'auth-logoutOtherDevices',
                'auth-getUser',
                'auth-sendVerificationEmail',
                'auth-deleteAvatar',
                'auth-getGenderTypes'
            ],

            '2' => [
                
            ],

            '3' => [
                
            ],

            '4' => [
                'auth-getRoleTypes',
                'auth-getAccountActionTypes'
            ],

            'exceptions' => [
                '4A' => [
                    'auth-test'
                ]
            ]
        ];
    }
}
