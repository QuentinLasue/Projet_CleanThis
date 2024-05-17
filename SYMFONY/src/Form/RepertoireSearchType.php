<?php

namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepertoireSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('tri_par', ChoiceType::class, [
                'choices' => array_merge(
                    ['id' => 'id', 'Statut' => 'statut'],
                    $this->getPropertiesChoices()
                ),
            ]);
    }

    private function getPropertiesChoices(): array
    {
        $reflClass = new \ReflectionClass(Operation::class);
        $properties = $reflClass->getProperties(\ReflectionProperty::IS_PRIVATE);

        $choices = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $choices[$propertyName] = $propertyName;
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
