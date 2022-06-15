<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Hobby;
class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
          $data= 
        [
        "Footbal",
        "singing",
        "Tennis",
        "Base Ball",
        "Tableau",
        "Business ",
        "Senior Data ",
        "Performance Analyst",
        "Business",
        "Reporting",
        "Volley Ball",
        "Reporting Analyst",
        "Diversity ",
        "Reporting brid",
        "Business Analyst",
        "Business Int",
        "Operations Ret",
        ] ;
        for ($i = 0; $i < count($data); $i++) 
        {

            // $product = new Product();
            // $manager->persist($product);
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);
        }
        $manager->flush();
    }
}
