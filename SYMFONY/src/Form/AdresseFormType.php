<?php

namespace App\Form;

use App\Entity\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdresseFormType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'label' => $this->translator->trans('form.adresse.number'),
                'attr' => [
                    'min' => 1,
                ]
            ])
            ->add('street', TextType::class, [
                'label' => $this->translator->trans('form.adresse.street')
            ])
            ->add('city', TextType::class, [
                'label' => $this->translator->trans('form.adresse.city')
            ])
            ->add('county', TextType::class, [
                'label' => $this->translator->trans('form.adresse.county')
            ])
            ->add('country', TextType::class, [
                'label' => $this->translator->trans('form.adresse.country')
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

