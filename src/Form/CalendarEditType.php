<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Services;

class CalendarEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Prestation',EntityType::class, [
                'mapped' => false,
                'class' => Services::class,
                'choice_label' => 'name'
            ])
            ->add('client')
             ->add('start', DateTimeType::class,[
                 'date_widget' => 'single_text'
             ])
            // ->add('end', DateTimeType::class,[
            //     'date_widget' => 'single_text'
            // ])
            ->add('description')
            ->add('all_day')
            //->add('background_color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
        ]);
    }
}
