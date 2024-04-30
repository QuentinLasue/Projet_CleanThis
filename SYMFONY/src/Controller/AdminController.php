<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/listePersonnel', name: 'app_admin')]
    public function index(Request $request, UserRepository $repo): Response
    {
        $userList = $repo->findAll();

        return $this->render('employe/admin/listePersonnel.html.twig', [
            'controller_name' => 'AdminController',
            'userList'=>$userList
        ]);
    }
}
