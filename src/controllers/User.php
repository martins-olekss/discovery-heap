<?php

namespace App\Controllers;

use \App\Models;

class User extends View
{

    public function test()
    {
        $template = $this->twig->load('about.twig');
        echo $template->render();
    }

    public function register()
    {
        Models\User::create(
            [
                'name' => "Test Khan",
                'email' => "testemailherer@testtest.com",
                'password' => password_hash("test123", PASSWORD_BCRYPT),
            ]
        );

        foreach(Models\User::all() as $user) {
            echo $user->name . '<br>';
        }
    }
}