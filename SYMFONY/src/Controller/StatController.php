<?php

namespace App\Controller;

use App\Entity\TypeOperation;
use App\Repository\OperationRepository;
use App\Repository\TypeOperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatController extends AbstractController
{
    #[Route('/stat', name: 'app_stat')]
    public function index(OperationRepository $operationRepository,TypeOperationRepository $typeOperationRepository,ChartBuilderInterface $chartBuilder): Response
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);

        $data = [];
        $labels = [];

        $TypeOperations = $typeOperationRepository ->findAll();
        $Operations =$operationRepository ->findAll();

        foreach ($TypeOperations as $TypeOperation) {
            $data[] = $TypeOperation->getPrix();
            
        }
        foreach ($Operations as $Operation) {
            $labels[] = $Operation->getDateStart();
            
        }


        $chart->setData([
            'labels' => [],
            'datasets' => [
                [
                    'label' => 'Données cleanthis',
                    'backgroundColor' => 'rgb(200, 99, 132)',
                    'borderColor' => 'rgb(200, 99, 132)',
                    'data' => $data,
                ],
            ],
        ]);

        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
        ]);

        return $this->render('stat/index.html.twig', [
            'chart' => $chart,
        ]);
    }

}


