<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OperationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RepertoireController extends AbstractController
{
    #[Route('admin/repertoire', name: 'app_repertoire')]
    public function index(Request $request, OperationRepository $operationRepository, PaginatorInterface $paginator): Response
    {
        $searchField = $request->query->get('searchField');
        $searchValue = $request->query->get('searchValue');
        
        if ($searchField && $searchValue) {
            switch ($searchField) {
                case 'id':
                    $operations = $operationRepository->findByCritereId($searchValue);
                    break;
                case 'statut':
                    $operations = $operationRepository->findByCritereStatut(['statut' => $searchValue]);
                    break;
                case 'dateForecast':
                    $operations = $operationRepository->findByCritereDatePrev($searchValue);
                    break;
                case 'client':
                    $operations = $operationRepository->findByCritereClient($searchValue);
                    break;
                case 'user':
                    $operations = $operationRepository->findByCritereUser($searchValue);
                    break;
                default:
                    $operations = $operationRepository->findByFieldAndTerm($searchField, $searchValue);
            }
        } else {
            // Si aucun champ de recherche n'est spécifié, récupérez toutes les opérations
            $operations = $operationRepository->findAll();
        }

        // Pagination des résultats
        $operations = $paginator->paginate(
            $operations,
            $request->query->getInt('page', 1),
            4
        );

        // Rendu du template avec les opérations et les valeurs de recherche
        return $this->render('repertoire/index.html.twig', [
            'operations' => $operations,
            'searchField' => $searchField,
            'searchValue' => $searchValue,
        ]);
    }
}
