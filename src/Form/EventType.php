<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;

class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('image', FileType::class, [
            'label' => 'event Photo',
            'required' => false,
            'attr' => ['class' => 'form-control-file'], // Ajoutez votre classe ici
            'mapped' => false, // This field is not mapped to an entity property
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                    ],
                    'mimeTypesMessage' 
                    => 'Please upload a valid image file',
                ])
            ],
        ])
        ->add('title', TextType::class, [
            'attr' => ['class' => 'form-control'], // Classe Bootstrap pour le champ texte
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => 'form-control'], // Classe Bootstrap pour le champ texte
                ])
            ->add('location', TextType::class, [
                'attr' => ['class' => 'form-control'], // Classe Bootstrap pour le champ texte
                ])
            ->add('date', DateType::class, [
                'attr' => ['class' => 'form-control'], // Classe Bootstrap pour le champ texte
                ])
            ->add('nbP', NumberType::class, [
                'attr' => ['class' => 'form-control'], // Classe Bootstrap pour le champ texte
                ])
            ->add('category',null , [
                'attr' => ['class' => 'form-control'], // Classe Bootstrap pour le champ texte
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
