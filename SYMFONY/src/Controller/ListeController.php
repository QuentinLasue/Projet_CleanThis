<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeController extends AbstractController
{
    #[Route('/liste', name: 'app_liste')]
    public function app_operation(OperationRepository $repo): Response
    {
        $operationsEnCours = $repo->findBy(['statut' => 'En cours']);
        $operationsTerminees = $repo->findBy(['statut' => 'Terminé']);

        return $this->render('liste.html.twig', [
            'operationsEnCours' => $operationsEnCours,
            'operationsTerminees' => $operationsTerminees,
        ]);
    }
}
