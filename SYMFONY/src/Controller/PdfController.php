<?php

namespace App\Controller;

use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Operation;
use Symfony\Component\Routing\Annotation\Route;

class PdfController extends AbstractController
{
    private $pdfGenerator;

    public function __construct(PdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    #[Route("/pdf/{id}", name: "app_pdf")]
    public function generatePdf(Operation $operation): Response
    {
        
        $data = [
            'operation' => $operation
        ];

        // Utiliser le service pour générer le PDF
        return $this->pdfGenerator->generatePdf($data);
    }
}
