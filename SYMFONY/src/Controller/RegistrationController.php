<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use App\Service\PasswordMailer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, PasswordMailer $passwordMailer, EntityManagerInterface $entityManager, RoleRepository $roleRepository): Response
    {
        // Récupérer tous les rôles
        $roles = $roleRepository->findAll();

        // Créer une nouvelle instance de l'entité User
        $user = new User();

        // Créer le formulaire avec les rôles passés en option
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'roles' => $roles
        ]);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        // Vérifier si le formulaire a été soumis et est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Générer un mot de passe aléatoire
            $generatedPassword = $this->generateRandomPassword();

            // Encoder le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $generatedPassword
                )
            );

            // Envoyer l'e-mail avec le mot de passe généré
            $passwordMailer->sendPasswordEmail($user->getEmail(), $generatedPassword);

            // Persister l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection vers la page de connexion après l'inscription
            $this->addFlash('success', 'Ajout d\' employé réussi');
            return $this->redirectToRoute('app_register');
        }

        // Rendre le formulaire et les rôles disponibles dans le template
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'roles' => $roles
        ]);
    }

    private function generateRandomPassword(int $length = 8): string
    {
        // Générer une chaîne de caractères aléatoire pour le mot de passe
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $password;
    }
}
