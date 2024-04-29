<?php

namespace App\Controller;

use App\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OperationController extends AbstractController
{
    /**
     * @Route("/operation", name="app_operation")
     */
    public function app_operation(): Response
    {
        // Récupérer toutes les opérations depuis la base de données
        $operations = $this->getDoctrine()->getRepository(Operation::class)->findAll();

        // Passer les opérations récupérées au modèle Twig
        return $this->render('operation.html.twig', [
            'operations' => $operations,
        ]);
    }
}
