<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RendezvousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateRendezvous',TextType::class, ['attr'=>[
                'class'=>'form-control']])
            ->add('heureRendezvous',DateTimeType::class, ['widget'=>'single_text',
            'required'=>false,
                ])

            ->add('patient',TextType::class,['attr'=>[

                'class'=>'form-control']])

            ->add('doctors',ChoiceType::class,['attr'=>[
                'class'=>'form-control'],
                'choices'=>$options['doctors'],
                'required'=>true,
                'mapped'=>false,
                 
                ])
                ->add('specialite',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                     'class'=>'form-control'],
                    'choices'=>$options['specialites']
                 
                    ])
             
        ->add('Enregistrer',SubmitType::class,['attr'=>[
                    'class'=>'btn btn-primary submit-btn']])                  
                         
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);

        // $resolver->setRequired('specialites');
        $resolver->setRequired('specialites');
        $resolver->setRequired('doctors');
    }
}
