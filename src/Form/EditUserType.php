<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
                'required' => true
            ])
            ->add('name', null, [
                'label' => 'Nom',
                'required' => true
            ])
            ->add('first_name', null, [
                'label' => 'Prénom',
                'required' => true
            ])
            ->add('pseudo', null, [
                'label' => 'pseudo',
                'required' => true
            ])
//            ->add('imageFile', FileType::class,[
//                'label' => 'Photo',
//                'required' => false
//            ])
            ->add('phone', TelType::class, [
                'label' => 'Téléphone',
                'required' => true
            ])
            ->add('address', null, [
                'label' => 'Votre Adresse',
                'required' => true
            ])
            ->add('postcode', null, [
                'label' => 'Code Postal',
                'required' => true
            ])
            ->add('city', null, [
                'label' => 'Ville',
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
