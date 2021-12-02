<?php

namespace App\Http\Permissions;

class RolePermission
{
    public static function getMinimumAccessLevel() {

        return [

            '1' => [
                '/api/test'
            ],

            '2' => [
                
            ],

            '3' => [
                
            ],

            '4' => [
                
            ],

            'exceptions' => [
                '1A' => [
                    '/api/test'
                ]
            ]
        ];
    }
}
