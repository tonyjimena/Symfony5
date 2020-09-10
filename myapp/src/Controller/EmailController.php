<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;


class EmailController extends AbstractController
{
    /**
     * @Route("/email", name="email")
     */
    public function sendEmail(Request $request, MailerInterface $mailer) : response
    {
        $email = (new Email())
            ->from('joseantoniojaja1995@gmail.com')
            ->to('tonyy-@hotmail.es')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($_POST['email'])
            //->priority(Email::PRIORITY_HIGH)
            ->subject($_POST['name'])
            ->text($_POST['message']);
            //->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);
        return $this->render('front/contact.html.twig', [
            'controller_name' => 'EmailController',
            'email' => 'Correo enviado'
        ]);
    }
}
