<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\TypeOperation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                'label'=>'Date de réalisation souhaité :'
            ])
            ->add('description',TextareaType::class ,[
                'label'=>'Description',
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }
}
