<?php

namespace App\Form;

use App\Entity\ActeMedical;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ActeMedicalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle',TextType::class,['label'=>'nom examen','attr' => [
                'class' => 'form-control-sm',
                'style' => 'color:black; font-size:20px;']
                
                ])

                ->add('patient',ChoiceType::class,[
                    'mapped'=>false,
                    'choices'=>$options['patients'],
                    'label' => 'Patient',
                    'attr'=>[
                       'class'=>'form-control form-control-sm',
                       'style' => 'color:black; font-size:20px;']
      
    
                 ])

                ->add('examen',ChoiceType::class,[
                    'mapped'=>false,
                    'choices'=>$options['examen'],
                    'label' => 'Patient',
                    'attr'=>[
                       'class'=>'form-control form-control-sm',
                       'style' => 'color:black; font-size:20px;']
                      
    
                ])
            ->add('submit',SubmitType::class,['label'=>'valider','attr' => [
                'class' => 'btn btn-primary  form-control ']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ActeMedical::class,
        ]);

        $resolver->setRequired('patient');
        $resolver->setRequired('examen');
       
    }
}
