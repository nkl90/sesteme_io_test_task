<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Brick\Money\Money;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $p1 = new Product();
         $p1
             ->changeName('Наушники')
             ->changePrice(Money::of(100, 'EUR'))
         ;

         $p2 = new Product();
         $p2
             ->changeName('Чехол для телефона')
             ->changePrice(Money::of(20, 'EUR'))
         ;
         $manager->persist($p1);
         $manager->persist($p2);

         $manager->flush();
    }
}
