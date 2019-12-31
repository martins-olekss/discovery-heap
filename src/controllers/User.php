<?php

namespace App\Controllers;

use \App\Models\User as UserModel;

class User extends View
{
    public function index() {}
    public function delete() {}
    public function update() {}
    public function showCreateForm() {
        $template = $this->twig->load('user/create.twig');
        echo $template->render();
    }

    public function create() {
        $password = $this->request->request->get('password');
        $passwordConfirm = $this->request->request->get('password_confirm');
        if ($password === $passwordConfirm) {
            $user = new UserModel();
            $user->name = $this->request->request->get('name');
            $user->email = $this->request->request->get('email');
            $user->password = password_hash($password, PASSWORD_DEFAULT);
            $user->save();
        } else {
            return false;
        }
    }
}