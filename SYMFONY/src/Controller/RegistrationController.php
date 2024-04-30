<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as MimeEmail;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,MailerInterface $mailer, EntityManagerInterface $entityManager, RoleRepository $roleRepository): Response
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
            $email = (new MimeEmail())
            ->from('cleanThis154@gmail.com')
            ->to($user->getEmail())
            ->subject('Votre mot de passe')
            ->html($this->renderView(
                'emails/password.html.twig',
                ['password' => $generatedPassword]
            ));
            $mailer->send($email);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('login_vue');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'roles'=> $roles
        
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
