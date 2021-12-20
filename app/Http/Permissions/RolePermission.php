<?php

namespace App\Http\Permissions;

/**
 * Klasa zawierająca informacje o dostępnych endpointach dla poszczególnych ról w serwisie
 */
class RolePermission
{
    /**
     * Metoda zwraca listę wszystkich endpointów (wraz z wyjątkami), na które może wejść użytkownik z daną rolą w systemie
     * 
     * @return array
     */
    public static function getMinimumAccessLevel(): array {

        return [

            '1' => [
                'auth-logoutMe',
                'auth-logoutOtherDevices',
                'auth-getUser',
                'auth-sendVerificationEmail',
                'auth-deleteAvatar',
                'auth-getGenderTypes',
                'auth-getUserAuthentication'
            ],

            '2' => [
                
            ],

            '3' => [
                
            ],

            '4' => [
                'auth-getRoleTypes',
                'auth-getAccountActionTypes',
                'auth-getUsers'
            ],

            'exceptions' => [
                '4A' => [
                    'auth-test'
                ]
            ]
        ];
    }
}
