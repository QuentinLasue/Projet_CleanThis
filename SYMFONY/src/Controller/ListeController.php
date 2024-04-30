<?php

namespace App\Controller;

use App\Entity\Operation; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ListeController extends AbstractController
{
    #[Route('/liste', name: 'app_liste')]
    public function index(): Response
    {
        // Récupérer toutes les entités depuis la base de données
        $entites = $this->getDoctrine()->getRepository(Operation::class)->findAll();
        
        return $this->render('liste.html.twig', [
            'entites' => $entites,
        ]);
    }
}
