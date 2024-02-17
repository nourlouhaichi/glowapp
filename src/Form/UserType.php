<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder
        //     ->add('cin')
        //     ->add('email')
        //     // ->add('roles')
        //     ->add('password')
        //     ->add('lastname')
        //     ->add('firstname')
        //     ->add('gender')
        //     ->add('datebirth')
        //     ->add('phone')
        //     // ->add('created_at')

            $builder
            ->add('lastname')

            ->add('firstname')

            ->add('cin')

            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Female' => 'female',
                    'Male' => 'male',
                ],
                'expanded' => true, //boutons radio 
            ])

            ->add('datebirth')

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER'
                    // 'Coach' => 'ROLE_COACH',
                    // 'Nutritionist' => 'ROLE_NUTRITIONIST'
                ],
                'multiple' => true, // permet de sélectionner plusieurs rôles
                'expanded' => true, // affiche les rôles sous forme de cases à cocher
                // 'required' => true, // indique que la sélection d'au moins un rôle est requise
            ])
            
            ->add('phone')

            ->add('email')
                        
            ->add('password'
            , PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                // 'mapped' => false,
                // 'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
