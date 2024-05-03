<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConfirmationController extends AbstractController
{
    #[Route('/confirmation', name: 'confirmation')]
    public function index(): Response
    {
        return $this->render('confirmation/index.html.twig', [
            'controller_name' => 'ConfirmationController',
        ]);
    }
}
