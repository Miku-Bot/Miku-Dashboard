<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class MailerController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(MailerInterface $mailer)
    {
        try {
            $email = (new Email())
                ->from(Address::fromString('Meek Test <meek.moe@systems.aiko-it-systems.eu>'))
                ->to('aiko@ragebears.de')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                ->replyTo('aiko@aiko-it-systems.eu')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text('Sending emails is fun again!')
                ->html('<p>See Twig integration for better HTML integration!</p>');
    
            $mailer->send($email);
    
            return new Response('<html><body>Success</body></html>');

        } catch (\Throwable $th) {
            throw new \Exception('Something went wrong!');
        }
        
    }
}