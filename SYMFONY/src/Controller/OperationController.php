<?php

namespace App\Controller;

use App\Entity\Operation;
use App\Repository\OperationRepository;
use App\Repository\TypeOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("/operation", name="app_operation")
     */
    #[Route('/operation', name: "app_operation" )]
    public function app_operation(OperationRepository $repo): Response
    {
    
        $operations = $repo->findAll();

        
        return $this->render('operation.html.twig', [
            'operations' => $operations,
        ]);
    }
}
