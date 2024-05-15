<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number',IntegerType::class,[
                'label'=>'Numéro :',
                'attr'=>[
                    'min'=>1,
                    ]
            ])
            ->add('street',TextType::class,[
                'label'=>'Rue :'
            ])
            ->add('city',TextType::class,[
                'label'=>'Ville :'
            ])
            ->add('county',TextType::class,[
                'label'=>'Département :'
            ])
            ->add('country',TextType::class,[
                'label'=>'Pays :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresse::class,
        ]);
    }
}
