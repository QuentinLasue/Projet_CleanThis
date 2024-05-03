<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer; // Ajout de l'importation manquante
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices' => array_combine($options['roles'], $options['roles']),
                'multiple' => false,
                'expanded' => false,
                'label'=>'Selectionner le rôle :'
            ])
            ->add('name', TextType::class,[
                'label'=>'Nom :'
            ])
            ->add('firstname', TextType::class,[
                'label'=>'Prénom :'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email :'
            ])
            ->add('submit', SubmitType::class, ['label' => 'Envoyer']);

            // ->add('plainPassword', PasswordType::class, [
            //     'mapped' => false,
            //     'attr' => ['autocomplete' => 'new-password'],
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Please enter a password',
            //         ]),
            //         new Length([
            //             'min' => 6,
            //             'minMessage' => 'Your password should be at least {{ limit }} characters',
            //             'max' => 4096,
            //         ]),
            //     ],
            // ])
        ;

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
        ]);
    }
}
