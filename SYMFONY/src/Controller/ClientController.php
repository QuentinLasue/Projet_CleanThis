<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TypeOperationRepository;
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
use Symfony\Component\Validator\Constraints\Email;

class ClientController extends AbstractController
{


    #[Route('/client', name: 'client')]
    // On demande a l'utilisateur si il a déja était client
    public function askClient(Request $request): Response
    {
        // Formulaire d'un input de type radio 
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
            } else if ($client === 'no') {
                // Rediriger vers le formulaire complet
                return $this->redirectToRoute('demande');
            }
        }
        return $this->render('client/client.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/client/name', name: 'getClient')]
    // on lui demande son adresse mail pour le retrouver dans la base de donnée
    public function getClient(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createFormBuilder()
            ->add('mail', EmailType::class, [
                'label' => 'Votre adresse mail',
                'required' => true,
                'constraints' => new Email(
                    message: "L'email renseigné n'est pas valide",
                )
            ])
            ->add('submit', SubmitType::class, ['label' => 'Suivant'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération des donnée du formulaire et stockage dans $mail
            $mail = $form->get('mail')->getData();
            //Récupérer les infos de la base de donnée
            $client = $em->getRepository(Client::class)->findOneBy(['mail' => $mail]);
            //Si le client existe 
            if ($client) {
                return $this->redirectToRoute('description', ['mail' => $client->getMail()]);
            } else {
                // informe l'utilisateur que le mail n'existe pas 
                $this->addFlash('error', "L'email renseignée n'a pas été trouvé.");
                return $this->redirectToRoute('getClient');
            }
        }

        return $this->render('client/mail.html.twig', [
            'form' => $form->createView()
        ]);
    }
    #[Route('/client/{mail}/description', name: 'description')]
    // si le client existe et est trouvée on lui demande l'opération qu'il veut réaliser
    public function askDescription(Client $client, Request $request, EntityManagerInterface $em): Response
    {

        $adresse = $client->getAdresse();
        $operation = new Operation();
        $form = $this->createForm(OperationFormType::class, $operation)
            ->add('submit', SubmitType::class, ['label' => 'Envoyer']);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operation->setStatut('En attente');
            // On le lie au info retrouver grace au mail client 
            $operation->setAdresse($adresse);
            $operation->setClient($client);

            $em->persist($operation);
            $em->flush();
            $this->addFlash('success', "Votre demande d'opération a été prise en compte.");
            return $this->redirectToRoute('home');
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
        // formulaire client/adresse et opération 
        $form = $this->createFormBuilder()
            ->add('client', ClientFormType::class, ['data' => $client])
            ->add('adresse', AdresseFormType::class, ['data' => $adresse])
            ->add('operation', OperationFormType::class, ['data' => $operation])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des donnée du formulaire
            $number = $form->get('adresse')['number']->getData();
            $street = $form->get('adresse')['street']->getData();
            $city = $form->get('adresse')['city']->getData();
            $county = $form->get('adresse')['county']->getData();
            $country = $form->get('adresse')['country']->getData();
            $photo = $form->get('operation')['photo']->getData();

            // Vérification si l'adresse existe en base de donnée 
            $adresseExistante = $em->getRepository(Adresse::class)->findOneBy([
                'number' => $number,
                'street' => $street,
                'city' => $city,
                'county' => $county,
                'country' => $country
            ]);
            // si elle existe alors on remplace $adresse pour qu'il récupére l'id 
            if ($adresseExistante) {
                $adresse = $adresseExistante;
            } else {
                // si non on enregistre et envoi
                $em->persist($adresse);
                $em->flush();
            }

            // Vérificatoin si le client existe dans la base de donnée
            //Récupération des donnée du formulaire et stockage dans $mail
            $mail = $form->get('client')['mail']->getData();
            $name = $form->get('client')['name']->getData();
            $firstname = $form->get('client')['firstname']->getData();
            // Récupérer les infos de la base de donnée si il existe
            $clientExist = $em->getRepository(Client::class)->findOneBy([
                'mail' => $mail,
                'name' => $name,
                'firstname' => $firstname
            ]);
            // si le client exist 
            if ($clientExist) {
                // on vérifie l'adresse 
                $clientAdresse = $clientExist->getAdresse();
                // si c'est la même 
                if (
                    $clientAdresse &&
                    $clientAdresse->getNumber() === $adresse->getNumber() &&
                    $clientAdresse->getStreet() === $adresse->getStreet() &&
                    $clientAdresse->getCity() === $adresse->getCity() &&
                    $clientAdresse->getCounty() === $adresse->getCounty() &&
                    $clientAdresse->getCountry() === $adresse->getCountry()
                ) {
                    $client = $clientExist;
                } else {
                    //si elle est différente  on la met a jour
                    $clientExist->setAdresse($adresse);
                    $em->persist($clientExist);
                    $em->flush();
                    $client = $clientExist;
                }
            } else {
                // si le client exist pas on lie avec l'adresse, on enregistre et on envoi
                $client->setAdresse($adresse);
                $em->persist($client);
                $em->flush();
            }

            // Vérification si une photo a été envoyé
            if ($photo) {
                // On génére le nom du fichier en récupérant l'extension en fonction du contenudu fichier
                $name = $client->getName();
                $firstName = $client->getFirstname();
                $safeFileName = $name . $firstName . '-' . uniqid() . '.' . $photo->guessExtension();

                // On déplace le fichier téléchargé vers la destination avec le nouveau nom 
                $photo->move(
                    $this->getParameter('photo_directory'),
                    $safeFileName
                );
                // On envoi le nom du fichier en bdd 
                $operation->setNamePhoto($safeFileName);
            }
            // On initialise le statut dans la base de donnée
            $operation->setStatut('En attente');
            // On lie avec l'adresse et le client et on enregistre envoi 
            $operation->setAdresse($adresse);
            $operation->setClient($client);
            $em->persist($operation);
            $em->flush();

            // message succes et redirection
            $this->addFlash('success', "Votre demande d'opération a été prise en compte.");
            return $this->redirectToRoute('home');
        }

        return $this->render('client/demande.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
