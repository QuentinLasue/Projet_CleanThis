<?php

namespace App\Controller;

use App\Repository\OperationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Operation;
use App\Form\OperationFormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class OperationController extends AbstractController
{

    public function terminerOperation($id): Response
    {
        return $this->redirectToRoute('app_liste');
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/operation", name: "app_operation")]
    public function operation(OperationRepository $repo): Response
    {

        $operations = $repo->findBy([
            "statut" => "A faire"
        ]);

        return $this->render('employe/operation.html.twig', [
            'operations' => $operations,
        ]);
    }

    #[Route("/operation/prendre/{id}", name: "app_operation_prendre")]
    public function prendreOperation(Operation $operation): Response
    {
        // Traitement pour transférer l'opération à "Ma Liste"
        // Vous pouvez mettre à jour le statut de l'opération ici
        // et la sauvegarder dans la base de données

        $operation->setStatut("En cours");
        $this->entityManager->persist($operation);
        $this->entityManager->flush();

        // Redirection vers la page "Ma Liste" après avoir pris l'opération
        return $this->redirectToRoute('app_operation');
    }
    #[Route("admin/AjoutOperation", name: "add_operation_list")]
    public function AddOperationWait(Request $request, OperationRepository $repo): Response
    {
        $page = $request->query->getInt('page', 1); // je regarde si j'ai un entier qui s'appelle pasge sinon je lui attribu 1 par default
        $limit = 5; // nombre d'élément par page
        // find avec la pagination mis en place dans OperationRepository et le critère du statut "En attente"
        $operationList = $repo->paginateOperationWait($page, $limit);
        $maxPage = ceil($operationList->count() / $limit); // $limit est le nombre d'éléments par page

        // Gestion pour l'affichage de la priorité de l'opération 
        $today = new \DateTime(); // Date actuelle 
        $limitHight = new \DateTime();
        $limitHight->modify('-1 week'); // une semaine avant la date actuelle
        $limitMedium = new \DateTime();
        $limitMedium->modify('-2 week'); // deux semaine avant la date actuelle

        return $this->render('employe/admin/ajoutOperation.html.twig', [
            'operationList' => $operationList,
            'maxPage' => $maxPage,
            'page' => $page,
            'today' => $today,
            'limitHight' => $limitHight,
            'limitMedium' => $limitMedium
        ]);
    }
    #[Route("admin/AjoutOperation/Details/{id}",name:"details_operation")]
    public function detailsOperationUpdate(Operation $operation,Request $request, EntityManagerInterface $em,OperationRepository $operationRepository): Response
    {   
        //Création du formulaire
        $form = $this->createForm(OperationFormType::class, $operation,[]);
        $form->add('submit',SubmitType::class,[
            'label'=>'Accepter l\'opération'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $operation->setStatut("A faire");
            $em->persist($operation);
            $em->flush();
            //message flash de succés
            $this->addFlash('success', "Votre modification a été prise en compte");

            //redirection à la page liste
            return $this->redirectToRoute('add_operation_list');
        }

        return $this->render('employe/admin/modifOperation.html.twig', [
            'form'=>$form->createView(),
            'operation'=>$operation
        ]);
    }
}
