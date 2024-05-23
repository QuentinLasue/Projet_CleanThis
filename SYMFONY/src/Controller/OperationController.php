<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Psr\Log\LoggerInterface;

class OperationController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage;
        $this->logger = $logger;
    }

    #[Route("/operation", name: "app_operation")]
    public function operation(OperationRepository $repo): Response
    {
        // Log the operation attempt
        $this->logger->info('Attempting to access operations');

        $this->denyAccessUnlessGranted('ROLE_USER');

        $user = $this->getUser();
        $this->logger->info('Authenticated user: ' . $user->getUserIdentifier());

        $roles = $user->getRoles();
        $this->logger->info('User roles: ' . implode(', ', $roles));

        $role = $roles[0];
        $this->logger->info('Assigned role: ' . $role);

        $maxOperations = match ($role) {
            'ROLE_ADMIN' => 5,
            'ROLE_SENIOR' => 3,
            'ROLE_APPRENTI' => 1,
            default => 0,
        };
        $this->logger->info('Max operations allowed: ' . $maxOperations);

        $operations = $repo->findBy([
            'statut' => 'A faire',
        ], null, $maxOperations);

        $this->logger->info('Number of operations found: ' . count($operations));

        return $this->render('employe/operation.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")]
    public function prendreOperation(Operation $operation): Response
    {
        // Log the take operation attempt
        $this->logger->info('Attempting to take operation with ID: ' . $operation->getId());

        $this->denyAccessUnlessGranted('ROLE_USER');

        // Logic to take the operation
        // Add your custom logic here
        $this->logger->info('Operation taken by user: ' . $this->getUser()->getUserIdentifier());

        return $this->redirectToRoute('app_operation');
    }
}
