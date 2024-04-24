<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Client;
use App\Entity\Operation;
use App\Entity\Adresse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ClientFormType;
use App\Form\OperationFormType;
use App\Form\AdresseFormType;
use Doctrine\ORM\EntityManagerInterface;

class ClientController extends AbstractController
{

    #[Route('/demande', name: 'demande')]
    public function askOperation(Request $request, EntityManagerInterface $em): Response
    {
        $client = new Client();
        $formClient = $this->createForm(ClientFormType::class, $client);
        $formClient->handleRequest($request);
        if ($formClient->isSubmitted() && $formClient->isValid()) {
            $em->persist($client);
            $em->flush();
        }

        $adresse = new Adresse();
        $formAdresse = $this->createForm(AdresseFormType::class, $adresse);
        $formAdresse->handleRequest($request);
        if ($formAdresse->isSubmitted() && $formAdresse->isValid()) {
            $em->persist($adresse);
            $em->flush();
        }

        $operation = new Operation();
        $formOperation = $this->createForm(OperationFormType::class, $operation);
        $formOperation->handleRequest($request);
        if ($formOperation->isSubmitted() && $formOperation->isValid()) {

            $em->persist($operation);
            $em->flush();
        }
        return $this->render('client/demande.html.twig', [
            'formOperation' => $formOperation->createView(),
            'formClient' => $formClient->createView(),
            'formAdresse' => $formAdresse->createView()

        ]);
    }
}
