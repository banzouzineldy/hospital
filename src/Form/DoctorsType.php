<?php

namespace App\Form;

use App\Entity\Doctors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DoctorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  
    
        $builder
        
            ->add('nom',TextType::class,['attr'=>[
                                'class'=>'form-control']])

            ->add('prenom',TextType::  class,['attr'=>[
                                'class'=>'form-control']])
            ->add('datenaissance',TextType::class,['attr'=>[
                                          'class'=>'form-control']])
            ->add('telephone',TextType::class,['attr'=>[
                                         'class'=>'form-control']])

            ->add('adresse',TextType::class,['attr'=>[
                                   'class'=>'form-control']])
            ->add('email',TextType::class,['attr'=>[
                                   'class'=>'form-control']])
            ->add('genre', TextType::class,['attr'=>[
                                 'class'=>'form-control']])
            ->add('file',TextType::class)
            ->add('specialite',ChoiceType::class,[
               'required'=>true,
               'mapped'=>false,
               'choices'=>$options['specialites']
            
               ]) 
               ->add('add',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doctors::class,
        ]);

        $resolver->setRequired('specialites');
    }
}
