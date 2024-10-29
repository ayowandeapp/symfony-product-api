<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Factory\ManufacturerFactory;
use App\Factory\ProductFactory;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class ProductTest extends ApiTestCase
{
    use ResetDatabase, Factories;
    public function testGetCollection(): void
    {
        ProductFactory::createMany(100);
        $response = static::createClient()->request('GET', '/api/products');

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
        $response = static::createClient()->request('GET', '/api/products?page=2');

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
        static::createClient()->request('POST', '/api/products', [
            'headers' => ['Content-type' => 'application/ld+json'],
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
        static::createClient()->request('PATCH', "/api/products/{$product->getId()}", [
            'headers' => ['Content-type' => 'application/merge-patch+json'],
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
        static::createClient()->request('POST', '/api/products', [
            'headers' => ['Content-type' => 'application/ld+json'],
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
}
