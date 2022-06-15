<?php

namespace App\Event;
use Symfony\Contracts\EventDispatcher\Event;
/**
 * Description of ListAllPersonneEvent
 *
 * @author HP
 */
class ListAllPersonneEvent extends Event{
    const LIST_ALL_PERSONNE_EVENT= 'personne.list_alls';
    
    public function __construct(private int $nbPersonne){
        
    }
    public function getNbPersonne(): int {
        return $this->nbPersonne;
    }
}
