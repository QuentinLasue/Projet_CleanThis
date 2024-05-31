<?php

namespace App\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Operation;
use App\Service\PdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class PdfController extends AbstractController
{
    private $pdfGenerator;

    public function __construct(PdfService $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    #[Route("user/pdf/{id}", name: 'generate_pdf')]



    public function generatePdf(Operation $operation): Response
    {
        
        $data = [
            'operation' => $operation
        ];

        // Utiliser le service pour générer le PDF
        return $this->pdfGenerator->generatePdf($data);
    }
}
