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
                //array_merge qui fusionne une liste de tableau, l'option 'role' pour le préremplissage et le tableau 'roles' pour la liste déroulante
                // array_combine créer un tableau a partir de deux tableau tableau 1 la clé tableau 2 la valeur
                'choices' => array_merge([$options['role'] => $options['role']], array_combine($options['roles'],$options['roles'])),
                'multiple' => false,
                'expanded' => false,
                'label' => 'Selectionner le rôle :',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Modifier']);;

        // Transformation des rôles entre tableau et chaîne de caractères
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
            'roles' => [], // Définir l'option roles avec une valeur par défaut
            'role'=>null // définir le role de l'utilisateur a une valeur par défaut
        ]);
    }
}
