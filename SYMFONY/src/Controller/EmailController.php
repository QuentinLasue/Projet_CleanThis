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
    #[Route("client/contact", name: "app_contact")]
    public function contact(Request $request, MailService $mailService): Response
    {
        $form = $this->createForm(ContactType::class);
    
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $submittedEmail = $form->get('email')->getData();
            $formContent = $form->get('content')->getData();
    
            $adminEmail = $this->getParameter('admin_email');
    
            $mailService->sendWelcomeMail($submittedEmail);
            $mailService->sendFormContent($adminEmail, $formContent,$submittedEmail);
    
            return $this->redirectToRoute('confirmation');
        }
    
        return $this->render('email/contact.html.twig', [
            'controller_name' => 'EmailController',
            'form' => $form->createView(),
        ]);
    }
}
