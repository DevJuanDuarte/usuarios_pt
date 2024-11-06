<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

class AuthTest extends TestCase {
    public function testRegister() {
        $authController = new AuthController();
        $response = $authController->register(['name' => 'Test User', 'email' => 'testuser@example.com', 'password' => 'testpassword']);
        $this->assertEquals(201, $response['status']);
    }

    public function testLogin() {
        $authController = new AuthController();
        $response = $authController->login(['email' => 'testuser@example.com', 'password' => 'testpassword']);
        $this->assertEquals(200, $response['status']);
    }
}
