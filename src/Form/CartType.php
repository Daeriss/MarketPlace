<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('input', null, array('label' => false))
            //->add('orderNumber')
            //->add('checkout')
            //->add('date)
            ->add('collect_date',  DateType::class, [
                'widget' => 'choice',
                'html5' => false,

    // adds a class that can be selected in JavaScript
    'attr' => ['class' => 'js-datepicker'],
                ])
            //->add('user')
            //->add('shop')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
