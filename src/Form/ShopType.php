<?php

namespace App\Form;

use App\Entity\Shop;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ShopType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom de votre commerce'
            ])
            ->add('adress', null, [
                'label' => 'Code Postal'
            ])
            ->add('road', null, [
                'label' => 'Adresse du commerce'
            ])
            ->add('img', FileType::class, [
                'label' => 'photo de profil',
                'help' => 'Une photo représentant votre travail (ex: plats, réalisations...etc)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '10240k',      // 10 Mo ?
                        'mimeTypes' => [
                            'image/*',              // SEULEMENT DES IMAGES
                        ],
                        'mimeTypesMessage' => 'SEULEMENT UN FICHIER IMAGE...',
                    ])
                ],
            ])
            ->add('paiement', null, [
                'label' => 'Moyens de paiements possibles',
                'help' => 'CB, ESP, CHEQUES, SANS CONTACT',
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
