<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Factory\ManufacturerFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ManufacturerTest extends ApiTestCase
{
    private const token = '230055b8534edcca508cc60b9c864d8a43763a01f4fe91b079e440308b600a3030345e88556d0b664e7966a306e88788540af1 1f308e789dd5a730ba';

    private HttpClientInterface $client;
    private EntityManagerInterface $em;

    protected function setUp(): void
    {
        $this->client = $this->createClient();
        $this->em = $this->client->getContainer()->get('doctrine')->getManager();

        $existingUser = $this->em->getRepository(User::class)->findOneBy(['email' => 'te1st@email.com']);

        if (!$existingUser) {
            $this->createUserAndToken();
        }
    }
    use ResetDatabase, Factories;
    public function testSomething(): void
    {
        ManufacturerFactory::createMany(10);
        $response = static::createClient()->request('GET', '/api/manufacturers', [
            'headers' => ['Content-type' => 'application/ld+json', 'X-AUTH-TOKEN' => self::token],
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['@id' => '/api/manufacturers']);
    }

    private function createUserAndToken(): void
    {
        $user = new User();
        $user->setEmail('te1st@email.com');
        $user->setPassword('12345');
        $this->em->persist($user);

        $token = new ApiToken();
        $token->setToken(self::token);
        $token->setUser($user);
        $this->em->persist($token);
        $this->em->flush();
    }
}
