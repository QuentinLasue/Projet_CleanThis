<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Psr\Log\LoggerInterface;

class LoginController extends AbstractController
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Log the login attempt
        $this->logger->info('Login attempt');

        if ($this->getUser()) {
            $this->logger->info('User already authenticated, redirecting to operation page');
            return $this->redirectToRoute('app_operation');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Log any login errors
        if ($error) {
            $this->logger->error('Login error: ' . $error->getMessage());
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Log the logout action
        $this->logger->info('User logged out');
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
