<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\AuthController;

class AuthTest extends TestCase {
    private AuthController $authController;

    protected function setUp(): void {
        $this->authController = new AuthController();
    }

    public function testRegister() {
        // Simulamos el registro
        $response = $this->authController->register([
            'name' => 'Test User', 
            'email' => 'testuser@example.com', 
            'password' => 'testpassword'
        ]);

        // Aseguramos que el status es 201 para éxito en el registro
        $this->assertEquals(201, $response['status']);
        $this->assertEquals('Usuario registrado exitosamente.', $response['message']);
    }

    public function testLogin() {
        // Primero registramos al usuario (si es necesario)
        $this->authController->register([
            'name' => 'Test User', 
            'email' => 'testuser@example.com', 
            'password' => 'testpassword'
        ]);

        // Luego probamos el login
        $response = $this->authController->login([
            'email' => 'testuser@example.com', 
            'password' => 'testpassword'
        ]);

        // Aseguramos que el status es 200 para éxito en el login
        $this->assertEquals(200, $response['status']);
        $this->assertEquals('Inicio de sesión exitoso.', $response['message']);
    }

    public function testRegisterWithExistingEmail() {
        // Intentamos registrar el mismo usuario dos veces para probar el caso de email duplicado
        $this->authController->register([
            'name' => 'Test User', 
            'email' => 'testuser@example.com', 
            'password' => 'testpassword'
        ]);

        $response = $this->authController->register([
            'name' => 'Test User', 
            'email' => 'testuser@example.com', 
            'password' => 'testpassword'
        ]);

        // Aseguramos que devuelve el status 400 para email duplicado
        $this->assertEquals(400, $response['status']);
        $this->assertEquals('El correo electrónico ya está registrado.', $response['message']);
    }
}
