<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use App\Domain\Service\PasswordMailer;
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
        $user = new User();
        $roles = $roleRepository->findAll();
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'roles' => $roles
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $generatedPassword = $this->generateRandomPassword();
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $generatedPassword
                )
            );

            // Envoi de l'e-mail avec le mot de passe généré
            $passwordMailer->sendPasswordEmail($user->getEmail(), $generatedPassword);

            $entityManager->persist($user);
            $entityManager->flush();

            // Redirection vers la page de connexion après l'inscription
            return $this->redirectToRoute('login_vue');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'roles' => $roles
        ]);
    }

    private function generateRandomPassword(int $length = 8): string
    {
        // Génération d'une chaîne de caractères aléatoire pour le mot de passe
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, strlen($characters) - 1)];
        }
        return $password;
    }
}
