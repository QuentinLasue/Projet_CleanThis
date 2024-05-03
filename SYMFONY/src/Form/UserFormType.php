<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email :'
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom :',
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => array_combine($options['roles'], $options['roles']),
                'multiple' => false,
                'expanded' => false,
                'label' => 'Selectionner le rôle :'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Modifier']);;

        // // Transformation des rôles entre tableau et chaîne de caractères
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // Transforme le tableau en chaîne de caractères
                    return implode(', ', $rolesArray);
                },
                function ($rolesString) {
                    // Transforme la chaîne de caractères en tableau
                    return explode(', ', $rolesString);
                }
            ));
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'nom' => null, // Définir la valeur par défaut pour le nom
            'roles' => [], // Définir l'option roles avec une valeur par défaut
        ]);
    }
}
