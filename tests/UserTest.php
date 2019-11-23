<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testRegisterUser()
    {
        $connection = new Database();
        $user = new User($connection);
        $post = array(
            'email' => 'unitTestEmail@email.com',
            'password' => '123simplepasswordHere',
            'password_confirm' => '123simplepasswordHere'
        );

        $result = $user->registerUser($post);

        $this->assertTrue($result);
    }
}