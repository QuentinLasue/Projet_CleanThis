<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YourController extends AbstractController
{
    /**
     * @Route("/base/nave", name="nave_route")
     */
    public function yourAction(): Response
    {

        return $this->render('base/nave.html.twig');

    }
}
