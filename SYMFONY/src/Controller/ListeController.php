<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\OperationRepository;
use App\Service\FactureMailService;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ListeController extends AbstractController
{
    private $entityManager;
    private $operationRepository;

    public function __construct(EntityManagerInterface $entityManager, OperationRepository $operationRepository)
    {
        $this->entityManager = $entityManager;
        $this->operationRepository = $operationRepository;
    }

    #[Route('user/operation/terminer/{id}', name: 'operation_terminer')]
    public function terminerOperation($id): Response
    {
        // Redirection vers la page de liste après avoir terminé l'opération
        return $this->redirectToRoute('app_liste');
    }

    #[Route('/user/liste', name: 'app_liste')]
    public function app_operation(Request $request, FactureMailService $email, PdfService $pdfContent): Response
    {
        if ($request->request->has('operation_id')) {
            $entityManager = $this->entityManager;
            $operationId = $request->request->get('operation_id');
    
            $operation = $this->operationRepository->find($operationId);
    
            if ($operation) {
                $operation->setDateEnd(new \DateTime());
                $operation->setStatut('Terminé');
                $entityManager->flush();
                
                $client = $operation->getClient();
                $pdf = $pdfContent->generatePdf(['operation'=>$operation]);
                $emailClient = $client->getMail();

          
                $email->sendFactureMail($emailClient, $pdf);
                // Redirection vers une page intermédiaire
                return $this->redirectToRoute('operation_terminer', ['id' => $operationId]);
            }
        }
    
        $operationsEnCours = $this->operationRepository->findBy(['statut' => 'En cours']);
        $operationsTerminees = $this->operationRepository->findBy(['statut' => 'Terminé']);
    
        return $this->render('employe/liste.html.twig', [
            'operationsEnCours' => $operationsEnCours,
            'operationsTerminees' => $operationsTerminees,
        ]);
    }
}
