<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ManufacturerFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ManufacturerTest extends ApiTestCase
{
    use ResetDatabase, Factories;
    public function testSomething(): void
    {
        ManufacturerFactory::createMany(10);
        $response = static::createClient()->request('GET', '/api/manufacturers');

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/manufacturers']);
    }
}
