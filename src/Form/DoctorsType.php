<?php

namespace App\Form;

use App\Entity\Doctors;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DoctorsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {  
    
        $builder
        
            ->add('nom',TextType::class,        ['attr'=>[
                                            'class'=>'form-control']])

            ->add('prenom',TextType::  class,  ['attr'=>[
                                               'class'=>'form-control']])

            ->add('datenaissance',DateType::class, ['widget'=>'single_text'])
                                                   
            ->add('telephone',TextType::class,  ['attr'=>[
                                                'class'=>'form-control']])

            ->add('adresse',TextType::class,    ['attr'=>[
                                                'class'=>'form-control']])

            ->add('email',  TextType::class,       ['attr'=>[
                                                'class'=>'form-control']])

            ->add('genre', ChoiceType::class,[
                'required'=>true,
                'expanded'=>true,
                'mapped'=>true,
                'multiple'=>false,
                'choices'=>['masculin'=>'M','feminin'=>'F']
                                 ])
          
            ->add('specialite',ChoiceType::class,[
               'required'=>true,
               'mapped'=>false,
               'attr'=>[
                'class'=>'form-control'],
               'choices'=>$options['specialites']
            
               ]) 
               ->add('brochure',  FileType::class,[
                'label' => 'telecharger l image',
                'mapped' => false, //mapped veut dire le champ brochure n'e
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/png',
                            'image/jpeg'
                        ],
                        'mimeTypesMessage' => 'l extension du fichier non accepte'
                    ])
                ],
            ])


               ->add('Enregistrer',SubmitType::class,['attr'=>[
                'class'=>'btn btn-primary submit-btn']])
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
