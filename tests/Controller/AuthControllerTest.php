<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testUserRegister(): void
    {
        $client = static::createClient();
        $data = [
            'username' => 'test@api.fr',
            'password' => 'passwordTest',
        ];

        $responseRegister = $client->request('POST', '/api/register', ['email' => $data['username'], 'password' => $data['password']]);
        $this->assertResponseStatusCodeSame('200');
        $this->assertJson(200, 'User '.$data['username'].' successfully created');
    }

    public function testUserLogin(): void
    {
        $client = static::createClient();
        $data = [
            'username' => 'test@api.fr',
            'password' => 'passwordTest',
        ];

        $responseLogin = $client->request(
            'POST',
            '/api/login_check',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data) // Convertir les données en JSON
        );

        $this->isJson();
        $this->assertResponseStatusCodeSame(200);

    }
}
