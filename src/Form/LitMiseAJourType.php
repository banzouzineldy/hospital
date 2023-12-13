<?php

namespace App\Form;

use App\Entity\Lit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class LitMiseAJourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numLit',TextType::class,        ['attr'=>[
                'class'=>'form-control']])

            ->add('status',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                    'class'=>'form-control'],
                'choices'=>['Libre'=>'Libre', 'Occupée'=>'Occupée']
                                 ])
                                 
            ->add('chambre',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['chambres']
             
                ])

                ->add('Editer',SubmitType::class,['attr'=>[
                    'class'=>'btn btn-primary submit-btn']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lit::class,
        ]);
        $resolver->setRequired('chambres');
    }
}
