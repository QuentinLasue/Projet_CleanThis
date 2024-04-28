<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailController extends AbstractController
{
    #[Route("/contact", name: "app_contact")]
    public function contact(Request $request, MailService $mailService): Response
    {
        // Création de l'instance du formulaire
        $form = $this->createForm(ContactType::class);

        // Traitement de la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Logique de traitement du formulaire ici

            // Envoi de l'e-mail de bienvenue
            $mailService->sendWelcomeMail();

            // Redirection ou autre action après soumission réussie
        }

        return $this->render('email/contact.html.twig', [
            'controller_name' => 'EmailController',
            'form' => $form->createView(), // Transmission du formulaire au template
        ]);
    }
}
