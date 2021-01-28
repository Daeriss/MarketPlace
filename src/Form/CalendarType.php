<?php

namespace App\Form;

use App\Entity\Calendar;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Repository\ServicesRepository;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Services;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CalendarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Prestation',EntityType::class, [
                'mapped' => false,
                'class' => Services::class,
                'choice_label' => 'name'
            ])
            ->add('client', null, [
                'label'=> 'Nom du client'
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                
                $calendar = $event->getData();
                $form = $event->getForm();
                if(null !== $calendar->getId()) {
    
                     $form->add('start', DateTimeType::class,[
                         'date_widget' => 'single_text',
                         'label' => 'Date et heure'
                     ]);
                }
            })
            // ->add('end', DateTimeType::class,[
            //     'date_widget' => 'single_text'
            // ])
            ->add('description')
            //->add('all_day')
           // ->add('background_color', ColorType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Calendar::class,
            "allow_extra_fields" => true
        ]);
    }
}
