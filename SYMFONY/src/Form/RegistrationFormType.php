<?php

namespace App\Form;

<<<<<<< HEAD
use App\Entity\Role;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
=======
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
>>>>>>> registration
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\CallbackTransformer; // Ajout de l'importation manquante
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

=======
>>>>>>> registration

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
<<<<<<< HEAD
            ->add('roles', ChoiceType::class, [
                'choices' => array_combine($options['roles'], $options['roles']),
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('name')
            ->add('firstname')
            ->add('email')
            ->add('submit', SubmitType::class, [
                'label' => 'register'
            ])
            // ->add('agreeTerms', CheckboxType::class, [
            //     'mapped' => false,
            //     'constraints' => [
            //         new IsTrue([
            //             'message' => 'You should agree to our terms.',
            //         ]),
            //     ],
            // ])
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
=======
            ->add('name')
            ->add('firstname')
            ->add('email') 
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
        ;
>>>>>>> registration
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
<<<<<<< HEAD
            'roles' => [], // Définir l'option roles avec une valeur par défaut
=======
>>>>>>> registration
        ]);
    }
}
