<?php

namespace App\Form;

use App\Entity\Presence;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('response', ChoiceType::class,[
                'choices'  => [
                    'Oui' => true,
                    'Non' => false],
                'label' => 'Seras-tu présent ?'
            ])
            ->add('guest', NumberType::class, [
                'label' => 'Nombre d\'invité(s) :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Presence::class,
        ]);
    }
}
