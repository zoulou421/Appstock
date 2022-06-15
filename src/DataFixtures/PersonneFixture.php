<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use \App\Entity\Personne;
class PersonneFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        
         for ($i = 0; $i < 20; $i++) {
            $personne = new Personne();
            $personne->setFirstname('Firstname '.$i);
            $personne->setName('Name '.$i);
            $personne->setAge(mt_rand(10, 100));
            $manager->persist($personne);
        
        }

        $manager->flush();
    }
}
