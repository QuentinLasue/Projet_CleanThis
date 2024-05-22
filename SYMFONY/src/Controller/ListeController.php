<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
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

    #[Route('/liste', name: 'app_liste')]
    public function app_operation(Request $request): Response
    {
        if ($request->request->has('operation_id')) {
            $entityManager = $this->entityManager;
            $operationId = $request->request->get('operation_id');
    
            $operation = $this->operationRepository->find($operationId);
    
            if ($operation) {
                $operation->setStatut('Terminé');
                $entityManager->flush();
    
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
