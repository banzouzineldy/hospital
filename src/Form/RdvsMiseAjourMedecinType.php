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

class RdvsMiseAjourMedecinType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       
        $builder
        ->add('date',DateTimeType::class,[ 'label' => 'date',
        'widget' => 'single_text',
        'attr'=>[
            'class' => 'my-date-field-class form-control form-control-sm ',
            'style' => 'color:red; font-size:12px; p-5;'
            
            
            ]])
        ->add('motif',TextareaType::class,[ 'label' => 'Modif',
        
        'attr'=>[
            'class'=>'form-control',
            'style' => 'color:red; font-size:12px; '
                ]])

        ->add('patient',ChoiceType::class,[
            'required'=>true,
           'mapped'=>false,
           'label' => 'Patient',
           'attr'=>[
            'class'=>'form-control'],
            'choices'=>$options['patients']

        ])

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
