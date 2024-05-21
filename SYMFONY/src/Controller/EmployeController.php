<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\EmployeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;


class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser(); // Obtenir l'utilisateur connecté
        $form = $this->createForm(EmployeType::class, $user );
        $form->handleRequest($request);

         // Vérifier si le formulaire a été soumis et est valide
         if ($form->isSubmitted() && $form->isValid()) {

            // Générer un mot de passe aléatoire
            $password = $form->get('password')->getData();
            $confirmPassword = $form->get('confirm_password')->getData();
              // Vérifier si les mots de passe correspondent
              if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Les mots de passe ne correspondent pas.');
                return $this->render('employe/profil.html.twig', [
                    'controller_name' => 'EmployeController',
                    'EmployeForm' => $form->createView(),
                    'user' => $user,
                ]);}
                
            // Encoder le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );

            // Persister l'utilisateur en base de données
            $entityManager->flush();

            // Redirection vers la page de connexion après l'inscription
            return $this->redirectToRoute('app_login');
        }

        return $this->render('employe/profil.html.twig', [
            'controller_name' => 'EmployeController',
            'EmployeForm' => $form->createView(),
            'user' => $user,
        ]);
    }
}
