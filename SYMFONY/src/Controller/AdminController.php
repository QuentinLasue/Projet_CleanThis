<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('admin/listePersonnel', name: 'admin.listePersonnel')]
    public function ListePersonnel(Request $request, UserRepository $repo): Response
    {
        $page = $request->query->getInt('page', 1); // je regarde si j'ai un entier qui s'appelle pasge sinon je lui attribu 1 par default
        $limit = 5; // nombre d'élément par page
        // find avec la pagination mis en place dans UserRepository
        $userList = $repo->paginateUser($page, $limit);
        $maxPage = ceil($userList->count() / $limit); // $limit est le nombre d'éléments par page

        // on render la bon fichier twig en lui envoyant la liste le nombre de page et la page actuelle
        return $this->render('employe/admin/listePersonnel.html.twig', [
            'controller_name' => 'AdminController',
            'userList' => $userList,
            'maxPage' => $maxPage,
            'page' => $page
        ]);
    }
    
    #[Route('admin/listePersonnel/{id}/modifier', name: 'admin.modifierPersonel')]
    public function Update(int $id, RoleRepository $roleRepository, UserRepository $userRepository, Request $request, EntityManagerInterface $em): Response
    {
        // on va chercher l'utilisateur grace a l'id puis on récupére son nom et prénom, ici pour l'utiliser dans le préremplissage des champs
        $user = $userRepository->find($id);
        $nom = $user->getName();
        $prenom = $user->getFirstname();
        // on récupére le tableau des rôles
        $roleTab = $user->getRoles();
        //le role d'indice 0 étant le role que l'on a attribué
        $role = $roleTab[0];
        // on récupére tous les roles pour la liste déroulante 
        $roles = $roleRepository->findAll();
        // création du formulaire avec envoi des roles pour la liste déroulante et du role de l'utilisateur pour le préremplissage
        $form = $this->createForm(UserFormType::class, $user, [
            'roles' => $roles,
            'role' => $role
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($user);
            $em->flush();
            // Ajout d'un message de réussite
            $this->addFlash('success', "Votre modification a été prise en compte");

            return $this->redirectToRoute('admin.listePersonnel');
        }

        // on render en envoyant le formulaire l'utilisateur pour la blueBar et les infos (nom,prenom,role) pour le préremplissage 
        return $this->render('employe/admin/modifier.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'nom' => $nom,
            'prenom' => $prenom,
            'role' => $role
        ]);
    }
    #[Route('admin/listePersonnel/delete/{id}', name: 'admin.delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        //après confirmation avec la function confirm() en js sur le twig 
        $em->remove($user);
        $em->flush();
        // Ajout d'un message de réussite
        $this->addFlash('success', "L'employé " . $user->getName() . " " . $user->getFirstname() . " a bien été supprimé.");


        return $this->redirectToRoute('admin.listePersonnel');
    }
}
