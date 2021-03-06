<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

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
                'attr' => ['class' => 'js-datepicker'],
                'label' => "Jour souhaité de collecte"
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
