<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Operation;
use Symfony\Component\Security\Core\Security;

class OperationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/operation", name: "app_operation")]
    public function operation(OperationRepository $repo): Response
    {
        $operations = $repo->findAll();

        return $this->render('operation.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")]
    public function prendreOperation(Operation $operation, Request $request, Security $security): Response
    {
        $user = $security->getUser();
        $operation->setUser($user); // Assigne l'utilisateur actuel à l'opération
        $operation->setStatut("En cours");
        $this->entityManager->flush();

        return $this->redirectToRoute('app_operation');
    }
}
