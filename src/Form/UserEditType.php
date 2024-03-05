<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
            $builder
            ->add('lastname')

            ->add('firstname')
            
            ->add('phone')

            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER',
                    'Coach' => 'ROLE_COACH',
                    'Nutritionist' => 'ROLE_NUTRITIONIST'
                ],
                'multiple' => true, // permet de sélectionner plusieurs rôles
                'expanded' => true, // affiche les rôles sous forme de cases à cocher
                // 'required' => true, // indique que la sélection d'au moins un rôle est requise
            ]);
                        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
