<?php

namespace App\Form;

use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie_pro',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('plan_pro',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('note_pro',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('date_pro')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
