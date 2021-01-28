<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Shop;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, [
                'label' => 'Nom du produit'
            ])
            ->add('description', null, [
                'help' => 'Ingrédients, marque, poids, prix/kilo, prix/litre...',
            ])
            ->add('price', null, [
                'label' => 'Prix'
            ])
            ->add('img', FileType::class, [
                'label' => 'photo à uploader',
                'mapped' => false,
                'required' => true,
                'help' => 'Une image représentant au mieux votre produit. Attention, une mauvaise image peut induire en erreur.',
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
            ->add('is_available', CheckboxType::class, [
                'label' => 'En stock ?',
                'help' => 'Votre produit est-il actuellement disponible ? Vous pourrez changer ce paramètre à tout moment.',
                'required' => false,
            ])
            // ->add('shop', EntityType::class, [ "class" => Shop::class, "choice_label" => "name" ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
