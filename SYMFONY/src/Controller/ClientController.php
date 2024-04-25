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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClientController extends AbstractController
{
    #[Route('/client', name: 'client')]
    public function askClient(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('client', ChoiceType::class, [
                'choices' => [
                    'Oui' => 'yes',
                    'Non' => 'no',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Êtes-vous déjà client ?'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Suivant'
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $client = $form->get('client')->getData();
            if ($client === 'yes') {
                // Rediriger vers le formulaire de client déjà membre
                return $this->redirectToRoute('getClient');
            } else {
                // Rediriger vers le formulaire complet
                return $this->redirectToRoute('demande');
            }
        }
        return $this->render('client/client.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/name', name: 'getClient')]
    public function getClient(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('mail', EmailType::class, ['label' => 'Votre adresse mail'])
            ->add('submit', SubmitType::class, ['label' => 'Suivant'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération des donnée du formulaire et stockage dans $mail
            $mail = $form->get('mail')->getData();
            //Récupérer les infos de la base de donnée
            $client = $em->getRepository(Client::class)->findOneBy(['mail' => $mail]);
            //Si le client n'existe pas
            if ($client) {
                return $this->redirectToRoute('description',['mail'=>$client->getMail()]);
            } else {
                // informe l'utilisateur que le mail n'existe pas 
                // $this->addFlash('danger',"L'email renseignée n'a pas été trouvé.");
            }
        }
        return $this->render('client/mail.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/client/{mail}/description', name: 'description')]
    public function askDescription(Client $client ,Request $request, EntityManagerInterface $em): Response
    {
        
        $adresse = $client->getAdresse();
        $operation = new Operation();
        $form = $this->createForm(OperationFormType::class, $operation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operation->setStatut('En attente');
            $operation->setAdresse($adresse);
            $operation->setClient($client);

            $em->persist($operation);
            $em->flush();
            return $this-> redirectToRoute('home');
        }

        return $this->render('client/description.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/demande', name: 'demande')]
    public function askOperation(Request $request, EntityManagerInterface $em): Response
    {
        $client = new Client();
        $adresse = new Adresse();
        $operation = new Operation();

        $form = $this->createFormBuilder()
            ->add('client', ClientFormType::class, ['data' => $client])
            ->add('adresse', AdresseFormType::class, ['data' => $adresse])
            ->add('operation', OperationFormType::class, ['data' => $operation])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operation->setStatut('En attente');

            $em->persist($adresse);
            $em->flush();
            $client->setAdresse($adresse);
            $em->persist($client);
            $em->flush();
            $operation->setAdresse($adresse);
            $operation->setClient($client);
            $em->persist($operation);
            $em->flush();
        }

        return $this->render('client/demande.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
