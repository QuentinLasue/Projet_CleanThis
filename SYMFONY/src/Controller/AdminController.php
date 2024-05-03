<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/listePersonnel', name: 'admin.listePersonnel')]
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

    #[Route('listePersonnel/{id}/modifier', name:'admin.modifierPersonel')]
    public function Update(User $user, RoleRepository $roleRepository,UserRepository $userRepository, Request $request, EntityManagerInterface $em):Response
    {

        $roles = $roleRepository->findAll();
        $form = $this->createForm(UserFormType::class,$user,[
            'roles' => $roles,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            $this->addFlash('success',"Votre modification a étéprise en compte");
    
            return $this->redirectToRoute('admin.listePersonnel');
        }


        return $this->render('employe/admin/modifier.html.twig',[
            'form'=>$form->createView(),
            'user'=>$user
        ]);
    }
}
