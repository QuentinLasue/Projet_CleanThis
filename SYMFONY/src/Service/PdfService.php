<?php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class PdfService
{
    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdf(array $data): Response
    {
        // Créer une instance de Dompdf avec des options
        $option = new Options();
        $option->set('isHtml5ParserEnabled', true);
        $option->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($option);

        // Générer le contenu HTML du PDF en utilisant un template Twig
        $html = $this->twig->render('pdf/pdf_template.html.twig', $data);

        // Charger le contenu HTML dans Dompdf
        $dompdf->loadHtml($html);

        // Rendre le PDF
        $dompdf->render();

        // Retourner une réponse avec le PDF en tant que contenu
        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="exemple.pdf"'
        ]);






    }

}
