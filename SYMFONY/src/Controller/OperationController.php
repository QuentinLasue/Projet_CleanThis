<?php

// src/Controller/OperationController.php

namespace App\Controller;

use App\Entity\Operation;
use App\Repository\OperationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route("/operation", name: "app_operation")]
    public function operation(OperationRepository $repo): Response
    {
        $this->logger->info('Tentative d\'accès aux opérations');
        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $this->logger->info('Utilisateur authentifié: ' . $user->getUserIdentifier());

        $roles = $user->getRoles();
        $this->logger->info('Rôles de l\'utilisateur: ' . implode(', ', $roles));

        $role = $roles[0];
        $this->logger->info('Rôle attribué: ' . $role);

        $maxOperations = match ($role) {
            'ROLE_ADMIN' => 5,
            'ROLE_SENIOR' => 3,
            'ROLE_APPRENTI' => 1,
            default => 0,
        };
        $this->logger->info('Nombre maximal d\'opérations autorisées: ' . $maxOperations);

        $operations = [];
        if ($maxOperations !== null && $maxOperations > 0) {
            $operations = $repo->findBy(['statut' => 'A faire'], null, $maxOperations);
        } else {
            $operations = $repo->findBy(['statut' => 'A faire']);
        }

        // Débogage : Affichage du contenu des opérations récupérées
        dump($operations);

        $this->logger->info('Nombre d\'opérations trouvées: ' . count($operations));

        return $this->render('employe/operation.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")]
    public function prendreOperation(Operation $operation): Response
    {
        $this->logger->info('Tentative de prendre l\'opération avec l\'ID: ' . $operation->getId());
        $this->denyAccessUnlessGranted('ROLE_USER');
        $this->logger->info('Opération prise par l\'utilisateur: ' . $this->getUser()->getUserIdentifier());
        return $this->redirectToRoute('app_operation');
    }
}
