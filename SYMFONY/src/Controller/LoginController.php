<?php 

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils; // Utilitaire pour l'authentification
use Psr\Log\LoggerInterface; // Interface pour l'enregistrement des logs
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

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

    #[Route(path: '/connect/google', name: 'connect_google')]
    public function connectGoogle(): Response
    {
        // redirection vers la page d'authentification google
        $googleClientId = $_ENV['GOOGLE_CLIENT_ID'];
        $redirectUri = 'http://127.0.0.1:8000/connect/google_check';
        $scope = 'email profile';

        $url = "https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=$googleClientId&redirect_uri=$redirectUri&scope=$scope";

        return $this->redirect($url);
    }

    #[Route(path: '/connect/google_check', name: 'connect_google_check')]
    public function connectGoogleCheck(Request $request, EntityManagerInterface $em): Response
    {
        // Récupération du code envoyé par google
        $code = $request->query->get('code');
        // si il n'existe pas redirection avec msg d'erreur
        if (!$code) {
            $this->addFlash('error', "Pas de code trouvée.");
            return $this->redirectToRoute('app_login');
        }
        // Construction de la requéte POST pour échanger le code contre un jeton d'accès
        $postData = [
            'code' => $code, // code d'autorisation reçu de google
            'client_id' => $_ENV['GOOGLE_CLIENT_ID'], // ID client de l'application
            'client_secret' => $_ENV['GOOGLE_CLIENT_SECRET'], // le secret client de l'pplication
            'redirect_uri' => 'http://127.0.0.1:8000/connect/google_check', // URI de redirection
            'grant_type' => 'authorization_code', //le type de grant pour OAuth2.0
        ];
        //Configuration des options pour la requéte HTTP POST
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n", // Définir le type de contenu comme URL encodé
                'content' => http_build_query($postData), // Encoder les données POST sous forme de chaîne de requête
            ],
        ];
        // Création du contexte de flux basé sur les options définies
        $context = stream_context_create($options);
        // L'URL du point de terminaison de l'API de Google pour obtenir un jeton d'accès
        $url = 'https://oauth2.googleapis.com/token';
        $response = file_get_contents($url, false, $context); // Exécution requéte, réponse stocker dans response

        if ($response === false) {
            $this->addFlash('error', "Error during request.");
            return $this->redirectToRoute('app_login');
        }
        // Décoder pour obtenir le token d'accès
        $data = json_decode($response, true);
        $accessToken = $data['access_token'];

        // Utiliser le jeton d'accès pour accéder aux informations de profil de l'utilisateur
        // Créer les en-têtes pour la requête GET à l'API Google
        $headers = [
            'Authorization: Bearer ' . $accessToken,
            'Accept: application/json'
        ];

        // Configuration de la requête GET
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", $headers)
            ]
        ];

        // Créer le contexte de flux pour la requête GET
        $context = stream_context_create($options);

        // URL de l'API Google pour récupérer les informations de profil de l'utilisateur
        $url = 'https://www.googleapis.com/oauth2/v1/userinfo';

        // Envoyer la requête GET à l'API Google pour récupérer les informations de profil de l'utilisateur
        $response = file_get_contents($url, false, $context);

        // Décoder la réponse JSON pour obtenir les informations de profil de l'utilisateur
        $userInfo = json_decode($response, true);

        // Récupérer l'email de l'utilisateur
        $userEmail = $userInfo['email'];
        // dd($userEmail);

        // On regarde si l'user existe dans labase de donnée
        $user = $em->getRepository(User::class)->findOneBy(['email' => $userEmail]);
        //Si le client existe 
        if ($user) {
            // si il existe on le connecte
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
            $this->container->get('security.token_storage')->setToken($token);
            $request->getSession()->set('_security_main', serialize($token));

            return $this->redirectToRoute('app_login');
        } else {
            // Sinon informe l'utilisateur que le mail n'existe pas et retour sur la page login
            $this->addFlash('error', "Votre adresse mail n'est pas lié à un compte.");
            return $this->redirectToRoute('app_login');
        }
    }
}
