<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
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
            // création d'un token de'authentification
            $token = new UsernamePasswordToken($user, 'main', $user->getRoles()); // objet utilisateur, main : firewall name, role associé
            // stocker le token dans le service de stockage des tokens de sécurité
            $this->container->get('security.token_storage')->setToken($token);
            // sauvegarde dans la session
            $request->getSession()->set('_security_main', serialize($token));//récupére la session et stocke 

            return $this->redirectToRoute('app_login');
        } else {
            // Sinon informe l'utilisateur que le mail n'existe pas et retour sur la page login
            $this->addFlash('error', "Votre adresse mail n'est pas lié à un compte.");
            return $this->redirectToRoute('app_login');
        }
    }
}
