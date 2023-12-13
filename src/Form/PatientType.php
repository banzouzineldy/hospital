<?php

namespace App\Form;

use App\Entity\Patient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PatientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::  class,['attr'=>[

                                'class'=>'form-control mt-5']])

            ->add('prenom',TextType::class,['attr'=>[

                            'class'=>'form-control mt-5']])

                            ->add('genre', ChoiceType::class,[
                               
                                'attr'=>[
                                'class'=>'form-control mt-5'],
                                'required'=>true,
                                'expanded'=>true,
                                'mapped'=>true,
                                'multiple'=>false,
                                'choices'=>['masculin'=>'M','feminin'=>'F'],
                                ])

                 ->add('age',NumberType::class, [
                    'label' => 'age',
                    'attr'=>[

                    'class'=>'form-control mt-5',
                    'min' => 0,
                    'max' => 100,
                    
                    ]])
                
            ->add('telephone',TextType::class,['attr'=>[
                
                         'class'=>'form-control mt-5']])

            ->add('nationalite',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                 'attr'=>[
                     'class'=>'form-control mt-5'],

                     'choices'=>$options['Nationalites']

            ])
            ->add('submit',SubmitType
            ::class,['label'=>'enregistrer','attr' => [
                'class' => 'btn btn-primary mt-5']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Patient::class,
        ]);
        $resolver->setRequired('Nationalites');
    }
}
