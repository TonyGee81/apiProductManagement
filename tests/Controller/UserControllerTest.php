<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserControllerWithoutToken(): void
    {
        $response = static::createClient()->request('GET', '/api/users', ['userId' => 10]);

        $this->assertResponseStatusCodeSame('401');
        $this->assertJson(401, 'JWT Token not found');
    }


}
