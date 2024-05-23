<?php 

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; // Utilitaire pour l'authentification
use Psr\Log\LoggerInterface; // Interface pour l'enregistrement des logs

class LoginController extends AbstractController
{
    private LoggerInterface $logger; // Déclaration d'une propriété privée de type LoggerInterface pour l'enregistrement des logs

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger; // Assignation du logger passé en argument au logger de la classe
    }

    #[Route(path: '/login', name: 'app_login')] // Annotation pour définir la route de connexion
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Enregistre la tentative de connexion dans les logs
        $this->logger->info('Tentative de connexion');

        if ($this->getUser()) { // Vérifie si l'utilisateur est déjà authentifié
            $this->logger->info('Utilisateur déjà authentifié, redirection vers la page d\'opération');
            return $this->redirectToRoute('app_operation'); // Redirige vers la page des opérations si l'utilisateur est déjà authentifié
        }

        // Récupère l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Récupère le dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        // Enregistre les erreurs de connexion dans les logs
        if ($error) {
            $this->logger->error('Erreur de connexion: ' . $error->getMessage());
        }

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]); // Rend le template de connexion avec les données nécessaires
    }

    #[Route(path: '/logout', name: 'app_logout')] // Annotation pour définir la route de déconnexion
    public function logout(): void
    {
        // Enregistre l'action de déconnexion dans les logs
        $this->logger->info('Utilisateur déconnecté');
        throw new \LogicException('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.'); // Lève une exception logique pour indiquer que cette méthode peut être vide et sera interceptée par la clé de déconnexion du pare-feu.
    }
}
