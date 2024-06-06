Version	1.0
Équipe de développement	Scrum Master : - Melon Samuel

Devellopeur : -Lasue Quentin
                       -Benarab Nordine
                       -Leitren Areis
                       -Achouri Moussa

Introduction 
Objectif : 

Développer une application pour la gestion des opérations de nettoyage destinées aux professionnels et particuliers, permettant la gestion des utilisateurs avec différents rôles, la création et le suivi des opérations demander par les clients, la génération de facture PDF envoyés par mail et la visualisation des statistiques de ventes via des graphiques, ainsi que la recherche dans les opérations qui sont en cour ou déjà effectué.

Vue d’ensemble de l’application
Description générale :

L’application CleanThis est conçue pour faciliter la gestion des opérations de nettoyage pour les entreprises de nettoyage professionnel et les particuliers. Elle permet une gestion centralisée des utilisateurs avec différents rôles, la création, le suivi et la clôture des opérations de nettoyage, ainsi que la génération automatique de rapports et de statistiques de vente. L’application vise à optimiser les processus internes, améliorer la satisfaction client grâce à un suivi précis des opérations, et fournir des outils de gestion avancés pour les administrateurs.

Contexte et justification : 

La société CleanThis a identifié un besoin pour une solution digitale permettant de gérer efficacement les opérations de nettoyage. Dans le but de facilité la communication entre les clients et l’entreprise, éviter les processus manuels pouvant entrainer des erreurs. L’application CleanThis résout ces problèmes en centralisant toutes les opérations dans une seule plateforme. Elle permet une gestion précise des opérations, améliore la traçabilité des tâches, et offre une vue d’ensemble claire des performances de l’entreprise
Utilisateurs et rôles
L’application compte 3 types d’utilisateurs :
	Expert (Admin)
	Senior
	Junior
En plus le client peut faire une demande d’opération sans besoin de se connecter.
Fonctionnalités
Les fonctionnalités sont différentes selon le rôle de l’utilisateurs.
	L’Expert (Admin) : 
	Accepter les opérations envoyer par les clients
	Prendre jusqu’à 5 opérations dans sa liste
	Créer les identifiants du personnel
	Modifier le personnel déjà existant (rôle, nom, prénom, mail)
	Consulter le chiffre d’affaire de l’entreprise
	Consulter le répertoire de toutes les opérations
	Consulter la liste du personnel
	Voir la liste des opérations à prendre
	Terminer une opération
	Modifier une demande d’opération
	Consulter les détails d’une opération

	Senior :
	Prendre jusqu’à 3 opérations dans sa liste
	Voir la liste des opérations à prendre
	Terminer une opération
	Consulter les détails d’une opération


	Junior :
	Prendre une opération dans sa liste
	Voir la liste des opérations à prendre
	Terminer une opération
	Consulter les détails d’une opération

Clients et opérations

Clients :

Les clients font une demande d’opération via un formulaire, quand l’opération est acceptée par un Expert (Admin) elle est gérée par un seul utilisateur. Un client peut effectuer autant de demande qu’il le souhaite. 
Opérations :

Les opérations sont regroupées par types : 
	Grosse (5000€)
	Moyenne (2500€)
	Petite (1000€)
	Custom (Prix fixé par l’Expert au moment de l’acceptation de l’opération)
Demande d’opération :

Les informations demandées lors d’une demande de d’opérations sont :
	Nom, prénom, adresse mail du client
	Adresse du lieu de l’opération
	Description de l’opération
	Photo (facultatif)
Intégration API Externe

Pour le formulaire de demande d’opération nous faisons a l’API adresse du gouvernement (API Gouv - Base Adresse Nationale) en utilisant la technologie AJAX, pour générer des adresse en fonction de ce que le client rentre dans les chams pour faciliter sa demande et pré remplir sa demande si ils sélectionne l’un des choix proposé.
try {
      //Appel de l'API Adresse avec la requête
      const response = await fetch(
        `https://api-adresse.data.gouv.fr/search/?q=${query}`
      );
      const data = await response.json();
} catch (error) {
      console.error("Erreur lors du fetch:", error);
    }


Technologie utilisé : 
Php (8.2)
Framework : Symfony (6.4)
Authentification via Google. (OAuth2.0)
Génération de documents : facture en fichier PDF et envoi par mail. (bibliothèque domPdf etcomposant mailer de Symfony)
Interactivité : Appels Ajax ou fetch pour appeler le backend.
Visualisation des statistiques : utilisations de chartJS pour les graphiques.
Utilisations de KNPPaginators pour le tri et la pagination dans le répertoire
Documentation du code
Introduction :
Cette partie a pour objectif de présenter le code source de l’application de manière générale.
Structure du projet : 
Les dossiers principaux sont : 
Dans notre projet : dans les assets nous retrouverons tous ce qui est lié au style, leCSS, du JavaScript, l’import de bootstrap (pour chartJS notamment), un dossier regroupant les photos utilisé dans l’application.
Dans le dossier templates nous retrouverons toutes les vues Twig ainsi que le thème de formulaire que nous avons créé.
Dans le dossier src nous retrouverons les controllers, les formulaires, les entités, les repository, les services et également les fixtures.
Dans le dossier migations les migrations effectuer vers la base de donnée pour l’implementer
 

Configuration :
Dans notre .env : 
APP_ENV pour l’environnement de notre projet soit dev ou prod.
Nous regroupons plusieur variables comme DATABASE_URL pour faire les appels à la base de donnée.
MAILER_DSN et MESSENGER_TRANSPORT_DSN : pour gérer les envois des mails.
GOOGLE_CLIENT_ID et GOOGLE_CLIENT_SECRET pour l’authentification via Google.
JWT_SECRET pour l’envoi d’un mail de réinitialisation de mot de passe valide 1 minute.
La configuration de la sécurité dans Symfony se fait dans le fichier security.yaml. Ce fichier définit les encodeurs pour les mots de passe, les fournisseurs d'utilisateurs, les pare-feu pour gérer les sessions utilisateur, et les contrôles d'accès pour restreindre les pages selon les rôles.

Entités : 

Les entités sont reprises du MLD et ont les mêmes relations que sur le MLD. Elles ont été généra à l’aide de la commande make:entity de Symfony.
Pour exemple voici l’entité Opérations :

#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: "/\S+/",
        message: "La description ne peut pas contenir uniquement des espaces."
    )]
    private ?string $description = null;

    #[ORM\Column(length: 100)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateStart = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateEnd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank()]
    #[Assert\GreaterThan("today")]
    private ?\DateTimeInterface $dateForecast = null;


    #[ORM\ManyToOne(inversedBy: 'operations')] 

    private ?Adresse $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    private ?TypeOperation $type = null;

    #[ORM\ManyToOne(inversedBy: 'operations')]
    private ?User $user = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $namePhoto = null;

    #[ORM\Column(nullable: true)]
    private ?float $customPrix = null;


A la suite de ce code se trouve les getters et les setters de chaque colonne : pour exemple voici ceux du statut : 
public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }


Controllers : 

Ils ont été généré à l’aide de la commande make :controller de Symfony. Voici la liste des controller et leurs utilités : 
	AdminController : 
	Gère l’affichage de la liste du personnel 
	La modification d’un personnel
	La suppression d’un personnel 
	ClientController : 
	Gère l’affichage des différents formulaires (demande nouveau client, vérification client existant, demande client existant) et leurs traitements avant envoi en base de données après vérification.
	ConfirmationController : 
	Rend la vue de confirmation en cas de demande d’aide par mail d’un client.
	EmailController : 
	Gère l’envoi du mail d’aide en cas de soumission du formulaire d’aide
	HomeController : 
	Gère le rendu de la page home
	ListeController : 
	Gère l’affichage de la liste d’opération qu’un utilisateur à prise et le changement du statut de « En cours » à « Terminer » si l’utilisateur click sur le bouton terminer. 
	LoginController : 
	Gère la connexion de l’utilisateur et la vérification des donnés soumis au formulaire de connexion pour valider la connexion. 
	Gère la connexion avec Google et vérifie si le mail Google existe dans notre base de donnée avant de se connecter.
	OperationController : 
	Gérer la limite d’opération autoriser par utilisateur
	Acceptation des opérations par les experts
	Modification des opérations en attente
	PdfController : 
	Génération des PDF à partir d’une vue Twig et envoi des donnés à cette vue pour modifier l’affichage en fonction de l’opérations en liens avec le PDF généré.
	RegistrationController : 
	Gère la création de nouvel utilisateur par les experts
	Génére un mot de passe aléatoire pour l’envoyer par mail et le hashage pour l’envoi en base de donnée.
	RepertoireController : 
	Gère les différents filtres et les recherche pour l’affichage en conséquence. 
	StatController :
	Gère l’envoi des données pour les différents affichages des trois graphiques.

Services : 
Dans les services 3 types de services utilisés, le pdf, les mails et le JWT.
Le PdfService s’occupe de la génération d’un pdf a partir de la vue Twig il est appeler dans le controller lié au PDF
Les Mails (FactureMailService, MailService,  PasswordMailer) gère les envois des mail (respectivement : facture, mail de bienvenu et mail de réception de la demande d’aide, mail pour le mot de passe oublié)
Le JWT pour le hachage du mot de passe. 
Les formulaires : 
Plusieurs formulaires sont identifiés sur le site, certains à des fins de modification et d’autre pour ajouter de nouvelle données en base de donnés. 
	AdresseFormType 
	Gestion des soumissions des adresses 
	ClientFormType 
	Gestion des soumissions des clients
	ContactType 
	Gestion des données que le client souhaite nous demander lors de sa demande d’aide
	EmployeType
	Pour la connexion
	OperationFormType
	Gestion des soumissions des opérations
	RegistrationFormType
	Pour la création de nouvel employée par les experts
	RepertoireSearchType
	Pour les recherche dans le répertoire
	ResetPasswordFormType
	Pour la création d’un nouveau mot de passe
	ResetPasswordrequestFormType
	Pour la demande d’envoi d’email de réinitialisation de mot de passe
	UserformType
	Pour la modification d’un employé existant

Extrait de code de controller 
Ajout d’opération :

Le ClientController est responsable des formulaires du client, de la vérification des données et de l’envoi en base de donnée.
Les dépendances : 
	OperationRepository : Un service injecté pour interagir avec la base de données et récupérer des entités Operation.
	ClientRepository : Un service injecté pour interagir avec la base de données et récupérer des entités Client.
	UserRepository : Un service injecté pour interagir avec la base de données et récupérer des entités User.
	AdresseRepository : Un service injecté pour interagir avec la base de données et récupérer des entités Adresse.
	EntityManagerInterface : Pour la gestion des opérations de persistance de l’ORM.

Méthodes :

	askClient
#[Route('/client', name: 'client')]
o	demande si le client est déjà client chez CleantThis
	getClient
#[Route('/client/name', name: 'getClient')]

o	Si il est client on récupère ses infos à partir de son email
	Askdescription 
#[Route('/client/{mail}/description', name: 'description')]

o	On demande les détails de l’opération uniquement pour la demande
	askOperation
#[Route('/demande', name: 'demande')]

o	Demande d’opération pour un client pas encore connu
Extrait de code : 
askOperation
public function askOperation(Request $request, EntityManagerInterface $em): Response
    {
        $client = new Client();
        $adresse = new Adresse();
        $operation = new Operation();
        // formulaire client/adresse et opération 
        $form = $this->createFormBuilder()
            ->add('client', ClientFormType::class, ['data' => $client])
            ->add('adresse', AdresseFormType::class, ['data' => $adresse])
            ->add('operation', OperationFormType::class, ['data' => $operation])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des donnée du formulaire
            $number = $form->get('adresse')['number']->getData();
            $street = $form->get('adresse')['street']->getData();
            $city = $form->get('adresse')['city']->getData();
            $county = $form->get('adresse')['county']->getData();
            $country = $form->get('adresse')['country']->getData();
            $photo = $form->get('operation')['photo']->getData();

            // Vérification si l'adresse existe en base de donnée 
            $adresseExistante = $em->getRepository(Adresse::class)->findOneBy([
                'number' => $number,
                'street' => $street,
                'city' => $city,
                'county' => $county,
                'country' => $country
            ]);
            // si elle existe alors on remplace $adresse pour qu'il récupére l'id 
            if ($adresseExistante) {
                $adresse = $adresseExistante;
            } else {
                // si non on enregistre et envoi
                $em->persist($adresse);
                $em->flush();
            }

            // Vérificatoin si le client existe dans la base de donnée
            //Récupération des donnée du formulaire et stockage dans $mail
            $mail = $form->get('client')['mail']->getData();
            $name = $form->get('client')['name']->getData();
            $firstname = $form->get('client')['firstname']->getData();
            // Récupérer les infos de la base de donnée si il existe
            $clientExist = $em->getRepository(Client::class)->findOneBy([
                'mail' => $mail,
                'name' => $name,
                'firstname' => $firstname
            ]);
            // si le client exist 
            if ($clientExist) {
                // on vérifie l'adresse 
                $clientAdresse = $clientExist->getAdresse();
                // si c'est la même 
                if (
                    $clientAdresse &&
                    $clientAdresse->getNumber() === $adresse->getNumber() &&
                    $clientAdresse->getStreet() === $adresse->getStreet() &&
                    $clientAdresse->getCity() === $adresse->getCity() &&
                    $clientAdresse->getCounty() === $adresse->getCounty() &&
                    $clientAdresse->getCountry() === $adresse->getCountry()
                ) {
                    $client = $clientExist;
                } else {
                    //si elle est différente  on la met a jour
                    $clientExist->setAdresse($adresse);
                    $em->persist($clientExist);
                    $em->flush();
                    $client = $clientExist;
                }
            } else {
                // si le client exist pas on lie avec l'adresse, on enregistre et on envoi
                $client->setAdresse($adresse);
                $em->persist($client);
                $em->flush();
            }

            // Vérification si une photo a été envoyé
            if ($photo) {
                // On génére le nom du fichier en récupérant l'extension en fonction du contenudu fichier
                $name = $client->getName();
                $firstName = $client->getFirstname();
                $safeFileName = $name . $firstName . '-' . uniqid() . '.' . $photo->guessExtension();

                // On déplace le fichier téléchargé vers la destination avec le nouveau nom 
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $safeFileName
                );
                // On envoi le nom du fichier en bdd 
                $operation->setNamePhoto($safeFileName);
            }
            // On initialise le statut dans la base de donnée
            $operation->setStatut('En attente');
            // On lie avec l'adresse et le client et on enregistre envoi 
            $operation->setAdresse($adresse);
            $operation->setClient($client);
            $em->persist($operation);
            $em->flush();

            // message succes et redirection
            $this->addFlash('success', "Votre demande d'opération a été prise en compte.");
            return $this->redirectToRoute('home');
        }

        return $this->render('client/demande.html.twig', [
            'form' => $form->createView()
        ]);
    }
Génération du PDF :

Le PdfController est responsable de la génération des PDF à l’aide du PdfService.
Extrait de code : 
PdfController :
class PdfController extends AbstractController{
    private $pdfGenerator;
    public function __construct(PdfService $pdfGenerator){
        $this->pdfGenerator = $pdfGenerator;
    }
    #[Route("user/pdf/{id}", name: 'generate_pdf')]
    public function generatePdf(Operation $operation): Response{  
        $data = [
            'operation' => $operation
        ] ;
        // Utiliser le service pour générer le PDF
        return $this->pdfGenerator->generatePdf($data);
    }
}
PdfService :
class PdfService{
    private $twig;
    public function __construct(Environment $twig){
        $this->twig = $twig;
    }
    public function generatePdf(array $data): Response{
        // Créer une instance de Dompdf avec des options
        $option = new Options();
        $option->set('isHtml5ParserEnabled', true);
        $option->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($option);

        // Générer le contenu HTML du PDF en utilisant un template Twig
        $html = $this->twig->render('pdf/pdf_template.html.twig', $data);
        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);
        // Rendre le PDF
        $dompdf->render();
        // Retourner une réponse avec le PDF en tant que contenu
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="exemple.pdf"'
        ]);
    }
}

Google Authentification : 

La sécurité et l'authentification sont des aspects cruciaux pour garantir que seules les personnes autorisées peuvent accéder aux fonctionnalités de l'application CleanThis et que les données sensibles des clients sont protégées. 
Nous n’évoquerons pas ici la partie de connexion autre que le google OAuth2.0, à part sur ses quelques points : 
La configuration de la sécurité dans Symfony se fait dans le fichier security.yaml. Ce fichier définit les encodeurs pour les mots de passe, les fournisseurs d'utilisateurs, et les contrôles d'accès pour restreindre les pages selon les rôles.
Symfony utilise des composants comme form_login pour l'authentification des utilisateurs et access_control pour restreindre l'accès aux routes en fonction des rôles. Les utilisateurs doivent s'authentifier pour accéder aux fonctionnalités de l'application, et leur accès est contrôlé par leurs rôles.

Le LoginController est responsable de l’authentification à l’aide d’un compte Google ou non.
Méthodes lié à l’authentification Google : 

	connectGoogle
 #[Route(path: '/connect/google', name: 'connect_google')]

o	Redirection vers la page Google à l’aide de notre GOOGLE_CLIENT_ID, pour que le client accepte de partager ses infos.
	connectGoogleCheck
  #[Route(path: '/connect/google_check', name: 'connect_google_check')]

o	Si le client accepte, il est redirigé pour avec un code que l’on utilise pour récupérer un acces_token que l’on échange contre ses informations, que l’on vérifie puis connecte et redirige côté utilisateur en le connectant. 
Extrait de code : 
connectGoogle : 
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

connectGoogleCheck : Echange du code contre le access_token

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

Utilisation du access_token pour accéder aux info de l’utilisateur : 
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

Vérification s’il est bien un utilisateur et connexion si c’est le cas: 
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

            return $this->redirectToRoute('app_operation');

Conclusion

Le projet CleanThis est une initiative ambitieuse visant à développer une application de gestion des opérations de nettoyage pour les professionnels et les particuliers. Avec un cahier des charges détaillé et des spécifications claires, l'équipe de développement a travaillé avec diligence pour mettre en œuvre chaque aspect de l'application de manière rigoureuse et efficace.
Grâce à l'utilisation de technologies modernes telles que Symfony, MariaDB, et l'authentification via Google, l'application CleanThis offre une expérience utilisateur fluide et sécurisée. Les fonctionnalités clés, telles que la gestion des utilisateurs et des rôles, la génération de rapports PDF, et l'intégration d'API externes, ont été implémentées avec succès pour répondre aux besoins spécifiques du client.
L'accent mis sur la qualité et la robustesse de l'application est évident à travers la documentation exhaustive du code. Ces éléments garantissent la fiabilité et la maintenabilité de l'application à long terme. Deplus, toutes les entrées utilisateur sont validées et nettoyées pour prévenir les attaques de type injection SQL et XSS.
En conclusion, le projet CleanThis représente un effort collaboratif réussi pour fournir une solution logicielle innovante et fonctionnelle pour la gestion des opérations de nettoyage. Avec son ensemble complet de fonctionnalités et sa structure bien pensée, CleanThis est prêt à répondre aux défis du marché et à apporter une valeur ajoutée significative à ses utilisateurs.
