<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture {
    public function load(ObjectManager $manager): void {

        $faker = Factory::create();
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
