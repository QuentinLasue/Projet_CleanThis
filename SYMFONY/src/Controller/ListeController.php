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

    #[Route('/liste', name: 'app_liste')]
    public function app_operation(Request $request): Response
    {
        // Vérifier si une opération doit être marquée comme terminée
        if ($request->request->has('operation_id')) {
            $entityManager = $this->entityManager;
            // Récupérer l'ID de l'opération à terminer depuis la requête
            $operationId = $request->request->get('operation_id');

            // Récupérer l'opération en cours
            $operation = $this->operationRepository->find($operationId);

            if ($operation) {
                // Mettre à jour le statut de l'opération en cours à "Terminé"
                $operation->setStatut('Terminé');
                $entityManager->flush();
            }
        }

        $operationsEnCours = $this->operationRepository->findBy(['statut' => 'En cours']);
        $operationsTerminees = $this->operationRepository->findBy(['statut' => 'Terminé']);

        return $this->render('liste.html.twig', [
            'operationsEnCours' => $operationsEnCours,
            'operationsTerminees' => $operationsTerminees,
        ]);
    }
}
