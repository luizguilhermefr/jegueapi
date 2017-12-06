<?php

use Laravel\Lumen\Testing\DatabaseTransactions;

class RegisterAndLoginTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Given a user that want to register,
     * perform the register normally.
     *
     * @test
     */
    public function testNormalRegister()
    {
        $userData = [
            'email' => 'jailsonmendes@sucodelaranja.com.br',
            'password' => 'essaEraAPeçaQueVcQueria',
            'password_confirmation' => 'essaEraAPeçaQueVcQueria',
            'username' => 'jailsonmendes',
            'description' => 'Jailson Mendes',
        ];
        $responseData = [
            'success' => true,
        ];
        $response = $this->json('POST', '/register', $userData);
        $response->assertResponseStatus(201);
        $response->seeJsonContains($responseData);
    }

    /**
     * Given a user that already registered,
     * passed a wrong password confirmation or already taken
     * username, perform the error.
     *
     * @test
     */
    public function testEmailTakenRegister()
    {
        factory(App\User::class)->create([
            'username' => 'ivonight',
            'email' => 'ivonight@unioeste.br',
        ]);
        $userData = [
            'email' => 'ivonight@unioeste.br',
            'password' => 'essaEraAPeçaQueVcQueria',
            'password_confirmation' => 'essaEraAPeçaQueVcQueria',
            'username' => 'ivonight',
            'description' => 'Ivonei',
        ];
        $responseData = [
            'error' => 'EMAIL_ALREADY_TAKEN'
        ];
        $response = $this->json('POST', '/register', $userData);
        $response->assertResponseStatus(409);
        $response->seeJsonContains($responseData);
    }

    /**
     * If no content provided, throw error.
     *
     * @test
     */
    public function testUsernameAlreadyTakenRegister()
    {
        factory(App\User::class)->create([
            'username' => 'ivonight',
            'email' => 'ivonight@unioeste.br',
        ]);
        $userData = [
            'email' => 'ivonei@unioeste.br',
            'password' => 'essaEraAPeçaQueVcQueria',
            'password_confirmation' => 'essaEraAPeçaQueVcQueria',
            'username' => 'ivonight',
            'description' => 'Ivonei',
        ];
        $responseData = [
            'error' => 'USERNAME_ALREADY_TAKEN'
        ];
        $response = $this->json('POST', '/register', $userData);
        $response->assertResponseStatus(409);
        $response->seeJsonContains($responseData);
    }

    /**
     * If email is invalid, throw error.
     *
     * @test
     */
    public function testInvalidEmail()
    {
        $userData = [
            'email' => 'ivonei@.br',
            'password' => 'essaEraAPeçaQueVcQueria',
            'password_confirmation' => 'essaEraAPeçaQueVcQueria',
            'username' => 'ivonight',
            'description' => 'Ivonei',
        ];
        $responseData = [
            'error' => 'INVALID_EMAIL'
        ];
        $response = $this->json('POST', '/register', $userData);
        $response->assertResponseStatus(400);
        $response->seeJsonContains($responseData);
    }

    /**
     * If passwords didn't match, throw error.
     *
     * @test
     */
    public function testInvalidPasswordConfirmation()
    {
        $userData = [
            'email' => 'ivonei@unioeste.br',
            'password' => 'essaEraAPeçaQueVcQueria',
            'password_confirmation' => 'essaEraAPeçaQueVcqueria',
            'username' => 'ivonight',
            'description' => 'Ivonei',
        ];
        $responseData = [
            'error' => 'INVALID_PASSWORD_CONFIRMATION'
        ];
        $response = $this->json('POST', '/register', $userData);
        $response->assertResponseStatus(400);
        $response->seeJsonContains($responseData);
    }

    /**
     * If no content provided, throw error.
     *
     * @test
     */
    public function testNoParametersRegister()
    {
        $responseData = [
            'error' => 'REQUIRED_PARAMETER'
        ];
        $response = $this->json('POST', '/register', []);
        $response->assertResponseStatus(400);
        $response->seeJsonContains($responseData);
    }

    /**
     * Given a user that already registered,
     * perform a successful login.
     *
     * @test
     */
    public function testNormalLogin()
    {
        factory(App\User::class)->create([
            'username' => 'edguy',
            'email' => 'edguy@unioeste.br',
            'password' => hash('sha256', 'myPassword'),
        ]);
        $userData = [
            'email' => 'edguy@unioeste.br',
            'password' => 'myPassword',
        ];
        $responseData = [
            'success' => true
        ];
        $response = $this->json('POST', '/login', $userData);
        $response->assertResponseStatus(200);
        $response->seeJsonContains($responseData);
    }

    /**
     * Given a user that isnt registered or tried to input a wrong
     * password, perform an error.
     *
     * @test
     */
    public function testInvalidLogin()
    {
        factory(App\User::class)->create([
            'username' => 'edguy',
            'email' => 'edguy@unioeste.br',
            'password' => hash('sha256', 'myPassword'),
        ]);
        $userData = [
            'email' => 'edguy@unioeste.uk',
            'password' => 'myPassword',
        ];
        $responseData = [
            'success' => false,
            'error' => 'INVALID_EMAIL_OR_PASSWORD'
        ];
        $response = $this->json('POST', '/login', $userData);
        $response->assertResponseStatus(403);
        $response->seeJsonContains($responseData);
        $userData = [
            'email' => 'edguy@unioeste.br',
            'password' => 'myPasswordWrong',
        ];
        $response = $this->json('POST', '/login', $userData);
        $response->assertResponseStatus(403);
        $response->seeJsonContains($responseData);
    }
}
