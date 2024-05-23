<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TypeOperationRepository;

class HomeController extends AbstractController
{
   
    #[Route('/', name:'home')]
    public function home(TypeOperationRepository $repo ): Response
    {
        $typesOperations = $repo->findAll();

        return $this->render('base.html.twig', [
            'typesOperations'=>$typesOperations
        ]);
    }
}
