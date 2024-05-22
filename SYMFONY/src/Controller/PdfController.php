<?php

namespace App\Controller;

use App\Service\PdfGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class PdfController extends AbstractController
{
    private $pdfGenerator;

    public function __construct(PdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function generatePdf(): Response
    {
        
        $data = [
            'variable' => 'Valeur à inclure dans le PDF'
        ];

        // Utiliser le service pour générer le PDF
        return $this->pdfGenerator->generatePdf($data);
    }
}
