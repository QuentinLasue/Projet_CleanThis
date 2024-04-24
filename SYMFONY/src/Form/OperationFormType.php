<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Client;
use App\Entity\Operation;
use App\Entity\TypeOperation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OperationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('dateForecast', null, [
                'widget' => 'single_text',
            ])
            ->add('type', EntityType::class, [
                'class' => TypeOperation::class,
                'choice_label' => 'name',
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
