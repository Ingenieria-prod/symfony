<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsuarioCouchDbType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('full_name', TextType::class, ['label'=>'Nombre Completo','attr'=>['class'=>'form-control']])
            ->add('rut', TextType::class, ['label'=>'RUT','attr'=>['class'=>'form-control']])
            ->add('username', TextType::class, ['label'=>'UserName','attr'=>['class'=>'form-control']])
            ->add('password', TextType::class, ['label'=>'Password','attr'=>['class'=>'form-control', 'type'=>'password']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'couchdb',
        ]);
    }
}
