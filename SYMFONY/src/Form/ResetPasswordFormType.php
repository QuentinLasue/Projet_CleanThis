<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Entrez votre mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'L\'email est obligatoire.',
                    ]),
                    new Length([
                        'min'=> 6,
                        'max'=> 50,
                        'minMessage'=> 'Votre mot de passe est trop court.',
                        'maxMessage'=> 'Votre mot de passe est trop long, il ne doit pas dépasser 50 caractères.'
                    ]),
                    new Regex([
                        'pattern'=> "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*(),.?\":{}|<>]).+$/",
                        'message'=> "Le mot de passe doit contenir une majuscule, une minuscule, et un caractère spéciale minimum."
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}