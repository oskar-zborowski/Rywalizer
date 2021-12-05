<?php

namespace App\Http\Permissions;

class RolePermission
{
    public static function getMinimumAccessLevel() {

        return [

            '1' => [

            ],

            '2' => [
                
            ],

            '3' => [
                
            ],

            '4' => [
                'auth-test'
            ],

            'exceptions' => [
                '4A' => [
                    'auth-test'
                ]
            ]
        ];
    }
}
