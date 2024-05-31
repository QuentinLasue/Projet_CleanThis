<?php

namespace App\Controller;

use App\Repository\TypeOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;
use App\Entity\TypeOperation;
use App\Repository\OperationRepository;

class StatController extends AbstractController
{
    #[Route('admin/stat', name: 'app_statistique')]
    public function index(TypeOperationRepository $typeOperationRepository, OperationRepository $operationRepository, ChartBuilderInterface $chartBuilder): Response
    {
        $prixGrosse = 0;
        $prixMoyenne = 0;
        $prixPetite = 0;
        $prixCustom = 0;
        $countGrosse = 0;
        $countMoyenne = 0;
        $countPetite = 0;
        $countCustom = 0;
        
        // Initialisation des tableaux pour les dates
        $dateGrosse = array_fill(1, 12, 0);
        $dateMoyenne = array_fill(1, 12, 0);
        $datePetite = array_fill(1, 12, 0);
        $dateCustom = array_fill(1, 12, 0);
        
        // Récupérer toutes les opérations
        $operations = $operationRepository->findAll();

        foreach ($operations as $op) {
            $month = (int)$op->getDateForecast()->format('n'); // Récupère le mois au format numérique sans zéro initial

            // Obtenir le type d'opération
            $typeOperation = $op->getType();
            $type = $typeOperation->getName();

            // Ajouter le prix de l'opération au prix total du type correspondant
            switch ($type) {
                case 'Grosse Operation':
                    $prixGrosse += $typeOperation->getPrix();
                    $countGrosse += 1;
                    $dateGrosse[$month] += 1;
                    break;
                case 'Moyenne Operation':
                    $prixMoyenne += $typeOperation->getPrix();
                    $countMoyenne += 1;
                    $dateMoyenne[$month] += 1;
                    break;
                case 'Petite Operation':
                    $prixPetite += $typeOperation->getPrix();
                    $countPetite += 1;
                    $datePetite[$month] += 1;
                    break;
                case 'Custom Operation':
                    $prixCustom += $typeOperation->getPrix();
                    $countCustom += 1;
                    $dateCustom[$month] += 1;
                    break;
            }
        }

        $totalPrix = $prixGrosse + $prixMoyenne + $prixPetite + $prixCustom;
        $totalCount = $countGrosse + $countMoyenne + $countPetite + $countCustom;

        // Création des graphiques
        $chart1 = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chart1->setData([
            'labels' => ["GROSSE", "MOYENNE", "PETITE", "CUSTOM"],
            'datasets' => [
                [
                    'backgroundColor' => ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(58, 242, 75)'],
                    'data' => [$prixGrosse, $prixMoyenne, $prixPetite, $prixCustom],
                ],
            ],
        ]);

        $chart2 = $chartBuilder->createChart(Chart::TYPE_BAR);
        $chart2->setData([
            'labels' => ["GROSSE", "MOYENNE", "PETITE", "CUSTOM"],
            'datasets' => [
                [
                    'label' => 'Nombre d\'opérations',
                    'backgroundColor' => ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(58, 242, 75)'],
                    'data' => [$countGrosse, $countMoyenne, $countPetite, $countCustom],
                ],
            ],
        ]);

        $chart3 = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart3->setData([
            'labels' => ['Janvier','Fevrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Decembre'],
            'datasets' => [
                [
                    'label' => 'Grosse Operation',
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgb(255, 99, 132)',
                    'data' => array_values($dateGrosse),
                ],
                [
                    'label' => 'Moyenne Operation',
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'data' => array_values($dateMoyenne),
                ],
                [
                    'label' => 'Petite Operation',
                    'backgroundColor' => 'rgba(255, 205, 86, 0.2)',
                    'borderColor' => 'rgb(255, 205, 86)',
                    'data' => array_values($datePetite),
                ],
                [
                    'label' => 'Custom Operation',
                    'backgroundColor' => 'rgba(58, 242, 75, 0.2)',
                    'borderColor' => 'rgb(58, 242, 75)',
                    'data' => array_values($dateCustom),
                ],
            ],
        ]);

        return $this->render('stat/index.html.twig', [
            'chart1' => $chart1,
            'chart2' => $chart2,
            'chart3' => $chart3,
            'countGrosse' => $countGrosse,
            'countMoyenne' => $countMoyenne,
            'countPetite' => $countPetite,
            'countCustom' => $countCustom,
            'totalCount' => $totalCount,
            'totalPrix' => $totalPrix,
        ]);
    }
}
