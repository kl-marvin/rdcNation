<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de Passe',
                 'required' => true
            ])
            ->add('name', null, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('firstName', null, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('phone',  TelType::class, [
                'label' => 'Téléphone',
                'required' => true
            ])
            ->add('pseudo', null, [
                'label' => 'Pseudo',
                'required' => true
            ])
            ->add('address', null, [
                'label' => 'Votre adresse',
                'required' => true
            ])
            ->add('city', null, [
                'label' => 'Vile',
                'required' => true
            ])
            ->add('postcode', null, [
                'label' => 'Code Postal',
                'required' => true
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
