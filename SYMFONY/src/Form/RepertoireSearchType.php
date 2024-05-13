<?php


namespace App\Form;

use App\Entity\Operation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RepertoireSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('repertoire', ChoiceType::class, [
                'label' => 'Trier par:',
                'choices' => $this->getPropertiesChoices(),
                'attr' => ['class' => 'form-control'],
            ]);
    }

    private function getPropertiesChoices()
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

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }
}
