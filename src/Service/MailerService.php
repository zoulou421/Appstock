<?php


namespace App\Service;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailerService;
/**
 * Description of MailerService
 *
 * @author HP
 */
class MailerService {
    //put your code here
    public function __construct(private MailerInterface $mailer, private $replyTo) {
    }
    public function sendEmail(
            $to='bonevy.beby@majorel.com', 
            $content='<p>See Twig integration for better HTML integration!</p>',
            $subject='Time for Symfony Mailer!'
            ): void
    {
        $email = (new Email())
            ->from('bonevybeby@gmail.com')
            ->to($to)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            //->text('Sending emails is fun again!')
            ->html($content);

         $this->mailer->send($email);

        // ...
         /** 
          * first parameters process by a service
          * 
          *  App\Service\MailerService:
                arguments:
                   $replyTo:'bonevybeby@gmail.com'
          */
    }
}
