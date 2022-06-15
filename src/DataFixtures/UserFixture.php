<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class UserFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct(
        private UserPasswordHasherInterface $hasher){
    }
    public function load(ObjectManager $manager): void
    {

        // $product = new Product();
        // $manager->persist($product);

        // creating  users
           $admin1= new User();
           $admin1->setEmail(email:'admin1@gmail.com');
           $admin1->setPassword($this->hasher->hashPassword($admin1,plainPassword:'admin'));
           $admin1->setRoles(['ROLE_ADMIN']);
           
           $admin2= new User();
           $admin2->setEmail(email:'admin2@gmail.com');
           $admin2->setPassword($this->hasher->hashPassword($admin2, plainPassword:'admin'));
           $admin2->setRoles(['ROLE_ADMIN']);
           
           $manager->persist($admin1);
           $manager->persist($admin2);
           
           for($i=1;$i<=5;$i++){
               $user = new User();
               $user->setEmail(email:"user$i@gmail.com");
               $user->setPassword($this->hasher->hashPassword($user, plainPassword:'user'));
               $manager->persist($user);
           }
           
        $manager->flush();
    }
    
    public static function getGroups(): array{
        //return ['group1', 'group2'];
        return ['user'];
    }
}
