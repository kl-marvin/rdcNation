<?php

namespace App\Form;

use App\Entity\CR;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'attr' => array('id' => 'editor'),
                'label' => 'Contenu',
                'required' => false
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text'
                ])
            ->add('author', null, [
                'label' => 'Auteur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CR::class,
        ]);
    }
}
