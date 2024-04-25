<?php
namespace App\Controller;

use App\Repository\TypeOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController extends AbstractController{
    
    #[Route('/', name:'home')]
    public function home(TypeOperationRepository $repo ): Response
    {
        $typesOperations = $repo->findAll();

        return $this->render('base.html.twig', [
            'typesOperations'=>$typesOperations
        ]);
    } 
}