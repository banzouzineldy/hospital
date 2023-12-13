<?php

namespace App\Form;

use App\Entity\Rdvs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RdvsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    { 
        $builder
            ->add('date',DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'attr' => [
                    'class' => 'my-date-field-class form-control form-control-sm ',
                    'style' => 'color:red; font-size:12px;',
                ],
                ])
            
            ->add('motif',TextareaType::class,['attr' => [
                'class' => 'form-control form-control-sm',
                'style' => 'color:red; font-size:12px;',
            ],])

            ->add('patient',ChoiceType::class,[
                'mapped'=>false,
                'choices'=>$options['patients'],
                'label' => 'Patient',
                'attr'=>[
                   'class'=>'form-control form-control-sm',
                   'style' => 'color:black; font-size:20px;']
                  

            ])
            ->add('emailsmedecin',ChoiceType::class,['choices'=>$options['emailmedecins'],
            'label'=>'medecin',
            
            'attr'=>[
                'class'=>'form-control form-control-sm']])

                ->add('specialite',ChoiceType::class,
                  [   
                   'mapped'=>false,
                    'choices'=>$options['specialites'],
                    'label' => 'Specialite',
                     'attr'=>[
                        'class'=>'form-control form-control-sm',
                        'style' => 'color:black; font-size:20px;']
                        
                        ])

            ->add('submit',SubmitType::class,['label'=>'soumettre','attr' => [
                'class' => 'btn btn-primary  form-control ']])
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
