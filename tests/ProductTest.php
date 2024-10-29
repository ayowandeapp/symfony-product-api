<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\ApiToken;
use App\Entity\User;
use App\Factory\ManufacturerFactory;
use App\Factory\ProductFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductTest extends ApiTestCase
{
    use ResetDatabase, Factories;

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
    public function testGetCollection(): void
    {

        ProductFactory::createMany(100);
        $response = $this->client->request('GET', '/api/products', [
            'headers' => ['X-AUTH-TOKEN' => self::token]
        ]);

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products',
            '@type' => 'Collection',
            'totalItems' => 100,
            'view' => [
                '@id' => '/api/products?page=1',
                '@type' => 'PartialCollectionView',
                'first' => '/api/products?page=1',
                'last' => '/api/products?page=10',
                'next' => '/api/products?page=2',
            ],
        ]);

        $this->assertCount(10, $response->toArray()['member']);
    }

    public function testPagination()
    {
        $response = $this->client->request('GET', '/api/products?page=2', [
            'headers' => ['X-AUTH-TOKEN' => self::token]
        ]);

        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $this->assertJsonContains([
            '@context' => '/api/contexts/Product',
            '@id' => '/api/products',
            '@type' => 'Collection',
            'totalItems' => 100,
            'view' => [
                '@id' => '/api/products?page=2',
                '@type' => 'PartialCollectionView',
                'first' => '/api/products?page=1',
                'previous' => '/api/products?page=1',
                'last' => '/api/products?page=10',
                'next' => '/api/products?page=3',
            ],
        ]);

        $this->assertCount(10, $response->toArray()['member']);
    }

    public function testCreateProduct(): void
    {
        $manufacturer = ManufacturerFactory::createOne();
        $this->client->request('POST', '/api/products', [
            'headers' => ['Content-type' => 'application/ld+json', 'X-AUTH-TOKEN' => self::token],
            'json' => [
                'npm' => 'wrist-watch',
                'name' => 'wrist-watch',
                'issueDate' => date('Y-m-d'),
                'description' => 'watch',
                'manufacturer' => "api/manufacturers/{$manufacturer->getId()}"
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'npm' => 'wrist-watch',
            'name' => 'wrist-watch',
            'issueDate' => date('Y-m-d') . 'T00:00:00+01:00',
        ]);

    }
    public function testUpdateProduct(): void
    {
        $product = ProductFactory::createOne();
        $this->client->request('PATCH', "/api/products/{$product->getId()}", [
            'headers' => ['Content-type' => 'application/merge-patch+json', 'X-AUTH-TOKEN' => self::token],
            'json' => [
                'npm' => 'wrist-watch-updated',
                'name' => 'wrist-watch-updated',
            ]
        ]);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'npm' => 'wrist-watch-updated',
            'name' => 'wrist-watch-updated',
            '@id' => "/api/products/{$product->getId()}"
        ]);

    }

    public function testInvalidCreateProduct(): void
    {
        $this->client->request('POST', '/api/products', [
            'headers' => ['Content-type' => 'application/ld+json', 'X-AUTH-TOKEN' => self::token],
            'json' => [
                'npm' => 'wrist-watch',
                'name' => 'wrist-watch',
                'issueDate' => date('Y-m-d'),
                'description' => 'watch',
                // 'manufacturer' =>  // Use an invalid IRI
            ]
        ]);
        $this->assertResponseStatusCodeSame(422);
        // $this->assertResponseHeaderSame('content-type', 'application/ld+json');
        $this->assertJsonContains([
            '@type' => 'ConstraintViolationList',
            'description' => 'manufacturer: This value should not be null.'
        ]);

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
