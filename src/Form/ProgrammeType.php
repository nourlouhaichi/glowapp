<?php

namespace App\Form;
use App\Entity\Categorypro;
use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre_pro',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            // ->add('titre_pro', TextType::class, [
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control input-validation',
            //         'minlength' => '2',
            //         'maxlength' => '50'
            //     ],
            // ])
            ->add('plan_pro',TextType::class, [
                'required' => false,
                'empty_data' => '',])

            ->add('note_pro',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('date_pro')
            ->add('placeDispo', TextType::class, [
                'attr'=> [
                    'class'=> 'form-control',
                ]
            ])
            ->add('prix', TextType::class, [
                'attr'=> [
                    
                    'class'=> 'form-control',
                ]
            ])
            ->add('placeDispo', TextType::class, [
                'attr'=> [
                    
                    'class'=> 'form-control',
                ]
            ])
            ->add('image', FileType::class, [
                'attr'=> ['class' => 'form-control'],
                'required' => false,
                'mapped' => false,
            ])
            ->add('categorypro', EntityType::class, ['class' => Categorypro::class,'choice_label' => 'name',]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
