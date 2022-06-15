<?php

namespace App\EventListener;
use \App\Event\AddPersonneEvent;
use \Psr\Log\LoggerInterface;
use App\Event\ListAllPersonneEvent;
use Symfony\Component\HttpKernel\Event\KernelEvent;
/**
 * Description of PersonneListener
 *
 * @author HP
 */
class PersonneListener {
    //put your code here
    public function __construct(private LoggerInterface $logger) {
        
    }
    public function onPersonneAdd(AddPersonneEvent $event) {
        $this->logger->debug("En train d'écouter l'evenement personne.add et une personne"
                . " vient d'être ajoutée et c'est".$event->getPersonne()->getName());
    }
    public function onListAllPersonnes(ListAllPersonneEvent $event) {
        $this->logger->debug("En train d'écouter le nombre des personnes dont la base est".$event->getNbPersonne());
    }
    public function onListAllPersonnes2(ListAllPersonneEvent $event) {
        $this->logger->debug("En train d'écouter le nombre des personnes du second listener dont la base est".$event->getNbPersonne());
    }
    public function logKernelRequest(KernelEvent $event) {
        dd($event->getRequest());
    }
}
