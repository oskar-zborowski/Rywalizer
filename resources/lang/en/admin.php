<?php

return [
    'admin-user' => [
        'title' => 'Users',

        'actions' => [
            'index' => 'Users',
            'create' => 'New User',
            'edit' => 'Edit :name',
            'edit_profile' => 'Edit Profile',
            'edit_password' => 'Edit Password',
        ],

        'columns' => [
            'id' => 'ID',
            'last_login_at' => 'Last login',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_repeat' => 'Password Confirmation',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
                
            //Belongs to many relations
            'roles' => 'Roles',
                
        ],
    ],

    'device' => [
        'title' => 'Devices',

        'actions' => [
            'index' => 'Devices',
            'create' => 'New Device',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'ip' => 'Ip',
            'uuid' => 'Uuid',
            'os_name' => 'Os name',
            'os_version' => 'Os version',
            'browser_name' => 'Browser name',
            'browser_version' => 'Browser version',
            
        ],
    ],

    'role' => [
        'title' => 'Roles',

        'actions' => [
            'index' => 'Roles',
            'create' => 'New Role',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'guard_name' => 'Guard name',
            
        ],
    ],

    'admin-user' => [
        'title' => 'Admin Users',

        'actions' => [
            'index' => 'Admin Users',
            'create' => 'New Admin User',
            'edit' => 'Edit :name',
        ],

        'columns' => [
            'id' => 'ID',
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'activated' => 'Activated',
            'forbidden' => 'Forbidden',
            'language' => 'Language',
            
        ],
    ],

    // Do not delete me :) I'm used for auto-generation
];