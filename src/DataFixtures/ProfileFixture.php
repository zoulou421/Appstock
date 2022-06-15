<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profile;
class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $profile1 = new Profile();
         $profile1->setRs("facebook");
         $profile1->setUrl('https://web.facebook.com/bonevy.beby/');
       
         
         $profile2 = new Profile();
         $profile2->setRs('tweeter');
         $profile2->setUrl('https://web.tweeter.com/bonevy.beby/');
       
         
         $profile3 = new Profile();
         $profile3->setRs('linkedin');
         $profile3->setUrl('https://web.linkedin.com/bonevy.beby/');
       
         
         $profile4 = new Profile();
         $profile4->setRs('Github');
         $profile4->setUrl('https://github.com/bonevy.beby/');
         
         $profile5 = new Profile();
         $profile5->setRs('GitLAB');
         $profile5->setUrl('https://gitlab.com/bonevy.beby/');
       
         
         $manager->persist($profile1);
         $manager->persist($profile2);
         $manager->persist($profile3);
         $manager->persist($profile4);
         $manager->persist($profile5);
         $manager->flush();
    }
}
