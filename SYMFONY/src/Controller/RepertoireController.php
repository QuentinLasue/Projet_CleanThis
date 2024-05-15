<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request; // Ajout de l'importation de Request
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Operation;
use App\Repository\OperationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class RepertoireController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/repertoire', name: 'app_repertoire')]
    public function index(Request $request, OperationRepository $operationRepository, PaginatorInterface $paginator): Response
    {
        $operations = $operationRepository->findAll();
        $operations = $this->entityManager->getRepository(Operation::class)->findAll();

        $operations = $paginator->paginate(
            $operations, /* query NOT result */
            $request->query->getInt('page', 1),
            5/*limit per page*/
        );
        
        return $this->render('repertoire/index.html.twig', [
            'operations' => $operations,
        ]);
    }
}