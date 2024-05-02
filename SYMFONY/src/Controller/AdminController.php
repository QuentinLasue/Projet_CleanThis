<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/listePersonnel', name: 'admin.listePersonnel')]
    public function index(Request $request, UserRepository $repo): Response
    {
        $userList = $repo->findAll();

        return $this->render('employe/admin/listePersonnel.html.twig', [
            'controller_name' => 'AdminController',
            'userList'=>$userList
        ]);
    }

    #[Route('admin/listePersonnel/{id}/modifier', name:'admin.modifierPersonel')]
    public function Update(User $user):Response
    {
        return $this->render('employe/admin/modifier.html.twig',[
            'user'=>$user
        ]);
    }
}
