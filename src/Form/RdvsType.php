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
            ->add('date',DateTimeType::class)
            ->add('dateFin',DateTimeType::class)
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
                
                [   
                   'mapped'=>false,
                    'choices'=>$options['specialites'],
            
                     'attr'=>[
                        'class'=>'form-control mt-5']])

            ->add('submit',SubmitType::class,['label'=>'soumettre','attr' => [
                'class' => 'btn btn-primary mt-5']])
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
