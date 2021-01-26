<?php

namespace App\Form;

use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
class HorairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Lundi', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Lundiclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Mardi', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Mardiclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Mercredi', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Mercrediclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Jeudi', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Jeudiclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Vendredi', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Vendrediclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Samedi', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Samediclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Dimanche', TimeType::class, [
                'input'  => 'datetime',
                'widget' => 'choice',
            ])
            ->add('Dimancheclose', TimeType::class, [
                'input' => 'datetime',
                'widget' => 'choice',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Shop::class,
        ]);
    }
}
