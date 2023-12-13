<?php

namespace App\Form;

use App\Entity\Hospitalisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HospitalisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeAdmission',TextareaType::class,   ['attr'=>[
                'class'=>'form-control'
                ]])
            ->add('motifAdmission',TextareaType::class,   ['attr'=>[
                'class'=>'form-control'
                ]])
            ->add('dateEntree',DateType::class, ['widget'=>'single_text'])

            ->add('dateSortie',DateType::class, ['widget'=>'single_text',
            'required'=>false,
            'mapped'=>false])

            ->add('motifSortie',ChoiceType::class,[
                'required'=>false,
                'mapped'=>false,
                'attr'=>[
                    'class'=>'form-control'],
                'choices'=>['Guéri'=>'Guéri', 'Décedé'=>'Décedé']
                                 ])

          

            ->add('patient',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['patients']
             
                ])
            ->add('pathologie',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['pathologies']
             
                ])
            ->add('chambre',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['chambres']
             
                ])
            ->add('lit',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['lits']
             
                ])
            ->add('agent',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['agents']
             
                ])
            ->add('service',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['services']
             
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
            'data_class' => Hospitalisation::class,
        ]);
        $resolver->setRequired('services');
        $resolver->setRequired('specialites');
        $resolver->setRequired('pathologies');
        $resolver->setRequired('lits');
        $resolver->setRequired('chambres');
        $resolver->setRequired('users');
        $resolver->setRequired('patients');
    }
}
