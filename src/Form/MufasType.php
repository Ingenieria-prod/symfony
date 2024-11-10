<?php

namespace App\Form;

use App\Entity\Mufas;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MufasType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('zonal',TextType::class,['attr'=> ['class'=>'form-control']])
            ->add('sitio',TextType::class,['attr'=> ['class'=>'form-control']])
            ->add('codigo_mufa',TextType::class,['attr'=> ['class'=>'form-control']])
            ->add('distancia_optica',NumberType::class,['attr'=> ['class'=>'form-control']])
            ->add('latitud',NumberType::class,['attr'=> ['class'=>'form-control']])
            ->add('longitud',NumberType::class,['attr'=> ['class'=>'form-control']])
            ->add('referencia',TextType::class,['attr'=> ['class'=>'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mufas::class,
        ]);
    }
}
