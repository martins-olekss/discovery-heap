<?php

namespace App\Controllers;

use \App\Models\User as UserModel;

class User extends View
{

    public function register()
    {
        UserModel::create(
            [
                'name' => "Test Khan",
                'email' => "testemailherer@testtest.com",
                'password' => password_hash("test123", PASSWORD_BCRYPT),
            ]
        );

        foreach(UserModel::all() as $user) {
            echo $user->name . '<br>';
        }
    }
}