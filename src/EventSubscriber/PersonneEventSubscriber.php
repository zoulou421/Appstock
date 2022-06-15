<?php

namespace App\EventSubscriber;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\AddPersonneEvent;
use App\Service\MailerService;
use \Psr\Log\LoggerInterface;
/**
 * Description of PersonneEventSubscriber
 *
 * @author HP
 */
class PersonneEventSubscriber implements EventSubscriberInterface{
    public function __construct(private MailerService $mailer, private LoggerInterface $logger) {
    }
    public static function getSubscribedEvents():array {
       return [
           AddPersonneEvent::ADD_PERSONNE_EVENT =>[ 'onAddPersonneEvent', 3000]
        ]; 
    }
    public function onAddPersonneEvent(AddPersonneEvent $event) {
        $personne = $event->getPersonne();
        $message="a été ajouté avec succès";
        $mailMessage=$personne->getFirstname().' '.$personne->getName().' '.$message;
        $this->logger->info("Envoi d'email pour ".$mailMessage);
        $this->mailer->sendEmail(content: $mailMessage, subject: 'mail sent from Eventsubscriber');
    }

}


