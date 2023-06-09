<?php

namespace App\Form;

use App\Entity\Rdvs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RdvsMiseAjourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
        ->add('date',DateTimeType::class)
        ->add('motif',TextareaType::class)
        ->add('patient',ChoiceType::class,[
            'required'=>true,
           'mapped'=>false,
           'attr'=>[
            'class'=>'form-control mt-5'],
            'choices'=>$options['patients']

        ])
        ->add('emailsmedecin',ChoiceType::class,['choices'=>$options['emailmedecins'],
        
        'attr'=>[
            'class'=>'form-control mt-5']])

            ->add('specialite',ChoiceType::class,
            
            [   'required'=>true,
               'mapped'=>false,
                'choices'=>$options['specialites'],
        
                 'attr'=>[
                    'class'=>'form-control mt-5']])

        ->add('submit',SubmitType::class,['label'=>'modifier','attr' => [
            'class' => 'btn btn-primary']])
    ;
}

public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => Rdvs::class,
    ]);
    $resolver->setRequired('patients');
    $resolver->setRequired('emailmedecins');
    $resolver->setRequired('specialites');
}








}
