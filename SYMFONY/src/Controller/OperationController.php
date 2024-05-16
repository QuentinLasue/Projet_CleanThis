<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Operation;


class OperationController extends AbstractController
{

    public function terminerOperation($id): Response
{
    return $this->redirectToRoute('app_liste');
}

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/operation", name: "app_operation")]
    public function operation(OperationRepository $repo): Response
    {
        
        $operations = $repo->findBy([
            "statut" => "A faire"
        ]);

        return $this->render('employe/operation.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")]
    public function prendreOperation(Operation $operation): Response
    {
        // Traitement pour transférer l'opération à "Ma Liste"
        // Vous pouvez mettre à jour le statut de l'opération ici
        // et la sauvegarder dans la base de données

        $operation->setStatut("En cours");
        $this->entityManager->persist($operation);
        $this->entityManager->flush();

        // Redirection vers la page "Ma Liste" après avoir pris l'opération
        return $this->redirectToRoute('app_operation');
    }
}
