<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Operation;
use App\Form\OperationFormType;

use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;

class OperationController extends AbstractController
{

    private $entityManager;
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    #[Route("user/operation", name: "app_operation")]

    public function operation(OperationRepository $repo, UserInterface $user): Response
    {
        $currentUser = $this->userRepository->findOneBy(['email' => $user->getUserIdentifier()]);
        $userOperationsCount = $repo->count(['user' => $currentUser, 'statut' => 'En cours']);
        $operations = $repo->findBy(['statut' => 'A faire']);

        $maxOperations = [
            'ROLE_EXPERT' => 5,
            'ROLE_SENIOR' => 3,
            'ROLE_JUNIOR' => 1,
        ];

        $userRole = $currentUser->getRoles()[0];


        return $this->render('employe/operation.html.twig', [
            'operations' => $operations,
            'userOperationsCount' => $userOperationsCount,
            'userRole' => $userRole,
            'maxOperations' => $maxOperations,
        ]);
    }


    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")]

    public function prendreOperation(Operation $operation, UserInterface $user, UserRepository $userRepo, OperationRepository $repo, Request $request): Response

    {
        $currentUser = $userRepo->findOneBy(['email' => $user->getUserIdentifier()]);
        $userOperationsCount = $repo->count(['user' => $currentUser, 'statut' => 'En cours']);


        $maxOperations = [
            'ROLE_EXPERT' => 5,
            'ROLE_SENIOR' => 3,
            'ROLE_JUNIOR' => 1,
        ];

        $userRole = $currentUser->getRoles()[0];
        $maxAllowed = $maxOperations[$userRole] ?? 0;

        if ($userOperationsCount < $maxAllowed) {
            $operation->setStatut("En cours");
            $operation->setUser($currentUser);
            $this->entityManager->persist($operation);
            $this->entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => true, 'newCount' => $userOperationsCount + 1]);
            }
        } else {
            $message = "Vous avez atteint le nombre maximal d'opérations autorisées.";
            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => false, 'message' => $message]);
            }
            $this->addFlash('error', $message);
        }


        return $this->redirectToRoute('app_operation');
    }

    #[Route("admin/AjoutOperation", name: "add_operation_list")]
    public function AddOperationWait(Request $request, OperationRepository $repo): Response
    {
        $page = $request->query->getInt('page', 1); // je regarde si j'ai un entier qui s'appelle pasge sinon je lui attribu 1 par default
        $limit = 5; // nombre d'élément par page
        // find avec la pagination mis en place dans OperationRepository et le critère du statut "En attente"
        $operationList = $repo->paginateOperationWait($page, $limit);
        $maxPage = ceil($operationList->count() / $limit); // $limit est le nombre d'éléments par page

        // Gestion pour l'affichage de la priorité de l'opération 
        $today = new \DateTime(); // Date actuelle 
        $limitHight = new \DateTime();
        $limitHight->modify('-1 week'); // une semaine avant la date actuelle
        $limitMedium = new \DateTime();
        $limitMedium->modify('-2 week'); // deux semaine avant la date actuelle


        return $this->render('employe/admin/ajoutOperation.html.twig', [
            'operationList' => $operationList,
            'maxPage' => $maxPage,
            'page' => $page,
            'today' => $today,
            'limitHight' => $limitHight,

            'limitMedium' => $limitMedium
        ]);
    }
    #[Route("admin/AjoutOperation/Details/{id}", name: "details_operation")]
    public function detailsOperationUpdate(Operation $operation, Request $request, EntityManagerInterface $em, OperationRepository $operationRepository): Response
    {
        $ope = $operation->getType();

        //Création du formulaire
        $form = $this->createForm(OperationFormType::class, $operation, []);
        // si l'opération est custom on ajoute un champ
            $form->add('prix', NumberType::class, [
                'label' => 'Entrez le prix de l\'opération',
                'mapped' => false,
            ]);
        // }
        $form->add('submit', SubmitType::class, [
            'label' => 'Accepter l\'opération'
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération du prix si il existe
            $prix = $form->get('prix')->getData();
            if ($prix) {
                $operation->setCustomPrix($prix);
            }

            $operation->setStatut("A faire");
            $em->persist($operation);
            $em->flush();
            //message flash de succés
            $this->addFlash('success', "Votre modification a été prise en compte");

            //redirection à la page liste
            return $this->redirectToRoute('add_operation_list');
        }

        return $this->render('employe/admin/modifOperation.html.twig', [

            'form' => $form->createView(),
            'operation' => $operation
        ]);
    }
}
