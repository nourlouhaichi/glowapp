<?php

namespace App\Form;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Programme;
use App\Entity\Objectif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ObjectifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('cin')
            ->add('objectif_o',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('description_o',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('categorie_o',TextType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('poid_o',NumberType::class, [
                'required' => false,
                'empty_data' => '',])
            ->add('taille_o',NumberType::class, [
                'required' => false,
                'empty_data' => '',])
            // ->add('programme')
            ->add('programme', EntityType::class, ['class' => Programme::class,'choice_label' => 'categorie_pro',]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Objectif::class,
        ]);
    }
}
