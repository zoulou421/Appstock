<?php

namespace App\Event;
use \Symfony\Contracts\EventDispatcher\Event;
use App\Entity\Personne;
/**
 * Description of AddPersonneEvent
 *
 * @author HP
 */
class AddPersonneEvent extends Event{
    //put your code here
    const ADD_PERSONNE_EVENT= 'personne.add';
    
    public function __construct(private Personne $personne){
        
    }
    public function getPersonne(): Personne {
        return $this->personne;
    }

}
