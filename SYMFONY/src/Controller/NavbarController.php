<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NavbarController extends AbstractController
{
    #[Route('/navbar', name: 'app_navbar')]
    public function index(): Response
    {
        return $this->render('navbar/index.html.twig', [
            'controller_name' => 'NavbarController',
        ]);
    }
  
}
