<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\CategorieProd;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File; // Add this line for the File constraint

class Produit1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('price')
            ->add('quantity')
            ->add('categorieProd', EntityType::class, [
                'class' => CategorieProd::class,
                'choice_label' => 'nomCa', // Assuming 'nomCa' is the property to display in the dropdown
                'placeholder' => 'Choose a category', // Optional: Display a placeholder option
            ])
            ->add('image', FileType::class, [
                'label' => 'Product Photo',
                'required' => false,
                'mapped' => false, // This field is not mapped to an entity property
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image file',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
