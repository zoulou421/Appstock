<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use \App\Entity\Job;
class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $data= 
        [
        "Senior Data and Reporting Analyst",
        "Reporting Analyst (ERP Finance)",
        "Portfolio Reporting Analyst",
        "Data Visualisation and Insights Analyst",
        "Tableau Developer / GCP Data Engineer",
        "Business Intelligence Analyst",
        "Senior Data and Reporting Analyst",
        "Performance and Reporting Analyst - Data Analyst",
        "Business & Reporting Analyst",
        "Reporting Analyst - Disputes",
        "BI Specialist (Power BI/Tableau)",
        "Reporting Analyst",
        "Reporting Analyst",
        "Diversity Reporting Analyst",
        "Reporting and Insight Analyst",
        "Data Analyst / Business Analyst",
        "Reporting Analyst",
        "Diversity Reporting Analyst",
        "Business Intelligence Analyst",
        "Operations Reporting Analyst",
        "Data Analyst",
        ] ;
       for($i=0;$i<count($data);$i++)
       {
            
       
           // $product = new Product();
            // $manager->persist($product);
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);
        }
        $manager->flush();
    }
}
