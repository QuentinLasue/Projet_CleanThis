<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Operation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Repository\UserRepository;
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
        $page = $request->query->getInt('page', 1);
        $limit = 5;
        $operationList = $repo->paginateOperationWait($page, $limit);
        $maxPage = ceil($operationList->count() / $limit);

        $today = new \DateTime();
        $limitHight = (clone $today)->modify('-1 week');
        $limitMedium = (clone $today)->modify('-2 weeks');

        return $this->render('employe/admin/ajoutOperation.html.twig', [
            'operationList' => $operationList,
            'maxPage' => $maxPage,
            'page' => $page,
            'today' => $today,
            'limitHight' => $limitHight,
            'limitMedium' => $limitMedium,
        ]);
    }
}
