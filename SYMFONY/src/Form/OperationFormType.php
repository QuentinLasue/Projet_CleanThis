<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\TypeOperation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class OperationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => TypeOperation::class,
                'choice_label' => 'name',
                'label'=>"Type d'opération souhaitée"
            ])
            ->add('dateForecast', DateType::class, [
                'widget' => 'single_text',
                'label'=>'Date de réalisation souhaité :',
                'html5'=>true,
                'attr'=>[
                    'min'=>(new \DateTime())->format('Y-m-d')// définit la date minimal a aujourd'hui
                ]
            ])
            ->add('description',TextareaType::class ,[
                'label'=>'Description'
            ])
            ->add('photo',FileType::class,[
                'label'=> 'Ajoutez une photo :',
                'required'=> false,
                'mapped'=> false, // Ne pas mappez ce champ à une propriété de l'entité Opération
                'constraints'=>[
                    // contraintes pour les File : https://symfony.com/doc/current/reference/constraints/File.html
                    new File([
                        'maxSize'=>'2M',
                        'maxSizeMessage'=>'La taille maximum de votre fichier ne doit pas dépasser 2 Mo.',
                        'mimeTypes'=>['image/jpeg','image/png'], // type de fichier autorisés
                        'mimeTypesMessage'=>'Le fichier doit être une image au format JPEG ou PNG.'
                    ])
                    ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class
        ]);
    }
}
