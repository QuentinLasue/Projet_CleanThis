<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OperationRepository;
use Knp\Component\Pager\PaginatorInterface;

class RepertoireController extends AbstractController
{
    #[Route('/repertoire', name: 'app_repertoire')]
    public function index(Request $request, OperationRepository $operationRepository, PaginatorInterface $paginator): Response
    {
        $searchField = $request->query->get('searchField');
        $searchTerm = $request->query->get('searchTerm');

        if ($searchField && $searchTerm) {
            $operations = $operationRepository->findByFieldAndTerm($searchField, $searchTerm);
        } else {
            $operations = $operationRepository->findAll();
        }

        $operations = $paginator->paginate(
            $operations,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('repertoire/index.html.twig', [
            'operations' => $operations,
        ]);
    }
}
