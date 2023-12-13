<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',TextType::class,['attr'=>[
             'class'=>'form-control'],
             
             'label'=>'email'])

            ->add('roles',ChoiceType::class,[
                 'attr'=>['class'=>'form-control'],
                'required'=>true,
                'multiple'=>false,
                'expanded'=>false,
                'choices'=>[
                'Medecin'=> 'ROLE_MEDECIN',
                'Admin'=>  'ROLE_ADMIN',
                'Agent'=>  'ROLE_AGENT', 
                ]
                ])
                /* ->add('agreeTerms', CheckboxType::class, [
                    'mapped' => false,
                    'constraints' => [
                        new IsTrue([
                            'message' => 'You should agree to our terms.',
                        ]),
                    ],
                ]) */
          
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                          'class' => 'form-control'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])

            ->add('nom',TextType::class, ['attr'=>[
                'class'=>'form-control']
                
                ])


            ->add('prenom',TextType::class,['attr'=>[
                'class'=>'form-control']
                ])


            ->add('datenaissance',DateType::class, ['widget'=>'single_text',
                'attr'=>[
                    'class'=>'form-control ']
            ])

            ->add('telephone', TextType::class, ['attr'=>[
                'class'=>'form-control ']
                ])
            ->add('adresse',TextType::class, ['attr'=>[
                'class'=>'form-control']
                ])

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

            ->add('brochure',FileType::class,[
                'label' => 'telecharger l image',
                'mapped' => false, //mapped veut dire le champ brochure n'e
                'required' =>false,
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

            ->add('qualification',ChoiceType::class,[
                'required'=>true,
                'mapped'=>false,
                'attr'=>[
                 'class'=>'form-control'],
                'choices'=>$options['qualifications']
             
                ])

                ->add('unite',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                     'class'=>'form-control'],
                    'choices'=>$options['unites']
                 
                    ])

                    ->add('service',ChoiceType::class,[
                        'required'=>true,
                        'mapped'=>false,
                        'attr'=>[
                        'class'=>'form-control'],
                        'choices'=>$options['services']
                                     
                        ])

                ->add('fonction',ChoiceType::class,[
                    'required'=>true,
                    'mapped'=>false,
                    'attr'=>[
                     'class'=>'form-control'],
                    'choices'=>$options['fonctions']
                 
                    ])
           
            ->add('submit',SubmitType::class,['label'=>'soumettre','attr' => [
            'class' => 'btn btn-primary']])
           
        ;

        $builder->get('roles')
        ->addModelTransformer(new CallbackTransformer(
            function ($rolesAsArray) {
                 return count($rolesAsArray) ? $rolesAsArray[0]: null;
            },
            function ($rolesAsString) {
                 return [$rolesAsString];
            }
    ));
    }
   
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);

        $resolver->setRequired('specialites');
        $resolver->setRequired('qualifications');
        $resolver->setRequired('unites');
        $resolver->setRequired('services');
        $resolver->setRequired('fonctions');
       
      
    }
}
