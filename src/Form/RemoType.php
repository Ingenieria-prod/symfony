<?php

namespace App\Form;

use App\Entity\Mufas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RemoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('zonal', ChoiceType::class, [
                'choices' => array_flip($options['zonal_choices']),
                'placeholder' => 'Seleccione una zona',
                'label' => 'Zonal',
                'required' => true,
                'attr' => ['class' => 'form-control select2']
            ])
            ->add('sitio', ChoiceType::class, [
                'choices' => [],
                'placeholder' => 'Seleccione un sitio',
                'label' => 'Sitio',
                'required' => true,
                'attr' => ['class' => 'form-control select2']
            ])
            ->add('cable', ChoiceType::class, [
                'choices' => [],
                'placeholder' => 'Seleccione un cable',
                'label' => 'Cable',
                'required' => true,
                'attr' => ['class' => 'form-control select2']
            ])
            ->add('distancia_corte', NumberType::class, [
                'mapped' => false,
                'attr' => ['class'=>'form-control']
            ]);
          
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mufas::class,
            'zonal_choices' => [],
            'csrf_protection' => true,
        ]);
    }
}
