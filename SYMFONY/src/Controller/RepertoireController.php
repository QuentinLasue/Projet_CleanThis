<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\RepertoireSearchType;
use App\Repository\OperationRepository; // Importez le repository


class RepertoireController extends AbstractController
{
    private $operationRepository;

    public function __construct(OperationRepository $operationRepository)
    {
        $this->operationRepository = $operationRepository;
    }

    #[Route('/repertoire', name: 'app_repertoire')]
    public function index(Request $request, OperationRepository $operationRepository): Response
    {
        $form = $this->createForm(RepertoireSearchType::class);
        $form->handleRequest($request);

        $results = $operationRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
           
           $criteria = $form->getData();
          
            $results = $this->operationRepository->search($criteria);
        }

        return $this->render('repertoire/index.html.twig', [
            'form' => $form->createView(),
            'results' => $results,
      

        ]);
    }
}
