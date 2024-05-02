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
        $page= $request->query->getInt('page',1); // je regarde si j'ai un entier qui s'appelle pasge sinon je lui attribu 1 par default
        $limit=5; // nombre d'élément par page
        // find avec la pagination mis en place dans UserRepository
        $userList = $repo->paginateUser($page, $limit);
        $maxPage = ceil($userList->count()/$limit); // $limit est le nombre d'éléments par page


        return $this->render('employe/admin/listePersonnel.html.twig', [
            'controller_name' => 'AdminController',
            'userList'=>$userList,
            'maxPage'=>$maxPage,
            'page'=>$page
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
