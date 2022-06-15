<?php

namespace App\Service;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

class Helpers{
    private $langue;
    //private Security $security;
    public function __construct($langue, private Security $security) {
        $this->langue=$langue;
        //$this->security= $security;
    }
    public function sayCc():string {
        return 'coucou';
    }
    public function getUser(): User {
        if($this->security->isGranted("ROLE_ADMIN")){
           $user=$this->security->getUser();
           if($user instanceof User){
               return $user;
           }
        } 
    }
}

