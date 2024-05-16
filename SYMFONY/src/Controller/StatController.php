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
    #[Route('/stat', name: 'app_statistique')]
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
        $formattedDates = [];

        // Récupérer toutes les opérations
        $operations = $operationRepository->findAll();

        

        // Formatage des dates
        foreach ($operations as $op) {
            $dateFormatted = $op->getDateForecast()->format('Y-m-d'); // Formate la date au format 'Y-m-d'
            $formattedDates[] = $dateFormatted;
        }

        // Calcul des prix et des compteurs
        foreach ($operations as $op) {
            $typeId = $op->getType();

            // Utiliser le TypeOperationRepository pour trouver le type correspondant
            $typeOperation = $typeOperationRepository->find($typeId);

            // Ajouter le prix de l'opération au prix total du type correspondant
            switch ($typeOperation->getName()) {
                case 'grosse':
                    $prixGrosse += $typeOperation->getPrix();
                    $countGrosse += 1;
                    break;
                case 'moyenne':
                    $prixMoyenne += $typeOperation->getPrix();
                    $countMoyenne += 1;
                    break;
                case 'petite':
                    $prixPetite += $typeOperation->getPrix();
                    $countPetite += 1;
                    break;
                case 'custom':
                    $prixCustom += $typeOperation->getPrix();
                    $countCustom += 1;
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
            'labels' => $formattedDates,
            'datasets' => [
                [
                    'label' => 'Évolution des opérations',
                    'backgroundColor' => ['rgb(255, 99, 132)', 'rgb(54, 162, 235)', 'rgb(255, 205, 86)', 'rgb(58, 242, 75)'],
                    'data' => [$countGrosse, $countMoyenne, $countPetite, $countCustom],
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
