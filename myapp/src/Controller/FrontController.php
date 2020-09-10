<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectsRepository;
use App\Entity\Projects;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Validator\Validator\ValidatorInterface;
//use App\Form\EmailFormType;

class FrontController extends AbstractController
{

    /**
     * @Route("/phpinfo", name="phpinfo")
     */
    public function phpinf()
    {
        return \phpinfo();
    }

    /**
     * @Route("/", name="front")
     */
    public function index()
    {
        return $this->render('front/index.html.twig', [
            'controller_name' => 'FrontController',
            'framework' => 'Texas Origins'
        ]);
    }
    /**
     * @Route("/services", name="services")
     */
    public function services()
    {
        return $this->render('front/services.html.twig', [
            'controller_name' => 'FrontController',
            'framework' => 'Texas Origins'

        ]);
    }

     /**
     * @Route("/info", name="info")
     */
    public function info()
    {
        return $this->render('front/info.html.twig', [
            'controller_name' => 'FrontController',
            'name' => 'tony',
            'lastname' => 'Jimena'
        ]);
    }
    
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(/*MailerInterface $mailer*/)
    {
        //$email = new Email();
        //$form = $this->createForm(EmailFormType::class, $email);

        /*$email = (new Email())
            ->from('joseantoniojaja1995@gmail.com')
            ->to('tonyy-@hotmail.es')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $mailer->send($email);*/

        return $this->render('front/contact.html.twig', [
            'controller_name' => 'FrontController',
            'name' => 'tony',
            'lastname' => 'Jimena',
            'email' => null
            //'form' => $form
        ]);
    }
}
