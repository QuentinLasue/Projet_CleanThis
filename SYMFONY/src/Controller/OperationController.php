<?php
namespace App\Controller;

use App\Entity\Operation;
use App\Repository\OperationRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    private LoggerInterface $logger; // Déclaration d'une propriété privée de type LoggerInterface

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger; // Assignation du logger passé en argument au logger de la classe
    }

    #[Route("/operation", name: "app_operation")] // Annotation pour définir une route
    public function operation(OperationRepository $repo): Response
    {
        // Enregistre la tentative d'accès aux opérations dans les logs
        $this->logger->info('Tentative d\'accès aux opérations');

        $this->denyAccessUnlessGranted('ROLE_USER'); // Vérifie que l'utilisateur est authentifié

        $user = $this->getUser(); // Récupère l'utilisateur actuel
        $this->logger->info('Utilisateur authentifié: ' . $user->getUserIdentifier());

        $roles = $user->getRoles(); // Récupère les rôles de l'utilisateur
        $this->logger->info('Rôles de l\'utilisateur: ' . implode(', ', $roles));

        $role = $roles[0]; // Récupère le premier rôle de l'utilisateur
        $this->logger->info('Rôle attribué: ' . $role);

        $maxOperations = match ($role) { // Détermine le nombre maximal d'opérations autorisées en fonction du rôle
            'ROLE_ADMIN' => 5,
            'ROLE_SENIOR' => 3,
            'ROLE_APPRENTI' => 1,
            default => 0,
        };
        $this->logger->info('Nombre maximal d\'opérations autorisées: ' . $maxOperations);

        $operations = $repo->findBy([ // Récupère les opérations en fonction de leur statut
            'statut' => 'A faire',
        ], null, $maxOperations);

        $this->logger->info('Nombre d\'opérations trouvées: ' . count($operations));

        return $this->render('employe/operation.html.twig', [ // Rend la vue avec les opérations récupérées
            'operations' => $operations,
        ]);
    }

    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")] // Annotation pour définir une route
    public function prendreOperation(Operation $operation): Response
    {
        // Enregistre la tentative de prise d'une opération dans les logs
        $this->logger->info('Tentative de prendre l\'opération avec l\'ID: ' . $operation->getId());

        $this->denyAccessUnlessGranted('ROLE_USER'); // Vérifie que l'utilisateur est authentifié

        // Logique pour prendre l'opération
        // Ajoutez votre logique personnalisée ici
        $this->logger->info('Opération prise par l\'utilisateur: ' . $this->getUser()->getUserIdentifier());

        return $this->redirectToRoute('app_operation'); // Redirige vers la liste des opérations
    }
}
