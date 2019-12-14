<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    public function testAddNotification()
    {
        $testMessage = 'This is message test';
        $this->assertTrue(in_array($testMessage, $_SESSION['notifications']));
    }
}