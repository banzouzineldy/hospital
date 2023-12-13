<?php

namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ChambreMiseAJourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numChambre',TextType::class,   ['attr'=>[
                'class'=>'form-control'
                ]])
            ->add('niveau',TextType::class,   ['attr'=>[
                'class'=>'form-control'
                ]])
                ->add('categorie',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                        'class'=>'form-control'],
                    'choices'=>['Officier'=>'Officier', 'Sous Officier'=>'Sous Officier', 'Vip'=>'Vip']
                                     ])
                ->add('description',TextareaType::class,   ['attr'=>[
                    'class'=>'form-control'
                    ]])
                ->add('status',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                        'class'=>'form-control'],
                    'choices'=>['Libre'=>'Libre', 'Occupée'=>'Occupée']
                                     ])
                ->add('service',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                     'class'=>'form-control'],
                    'choices'=>$options['services']
                 
                    ])
                ->add('unite',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                     'class'=>'form-control'],
                    'choices'=>$options['unites']
                 
                    ])
            
            ->add('Editer',SubmitType::class,['attr'=>[
                'class'=>'btn btn-primary submit-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
        ]);
        $resolver->setRequired('services');
        $resolver->setRequired('unites');
    }
}
