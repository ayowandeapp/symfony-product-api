<?php

namespace App\DataFixtures;

use App\Factory\ManufacturerFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        // $manager->flush();
        // ManufacturerFactory::createMany(10);
        ProductFactory::createMany(10);
        UserFactory::createMany(5);
    }
}
