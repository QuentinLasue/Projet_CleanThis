<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    #[Route("/contact", name: "app_contact")]
    public function contact(MailService $mailService): Response
    {
        $mailService->sendMail(
            "cleanthis153@gmail.com",
            "mail via un service",
            'email/contact.html.twig',
            [
                'userName' => "John Doe",
                'message' => "lorem ipsum dolor sit amet consectetur adipicing elit.lib Atque",
            ],
            "coursinsy2s@gmail.com"
        );

        return $this->render('email/contact.html.twig', [
            'controller_name' => 'EmailController',
            'userName' => 'John Doe', // Pass data to the template
            'message' => 'Lorem ipsum dolor sit amet consectetur adipicing elit.lib Atque' // Pass data to the template
        ]);
    }
}
