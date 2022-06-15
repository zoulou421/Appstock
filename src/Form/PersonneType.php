<?php

namespace App\Form;

use App\Entity\Personne;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Profile;
use App\Entity\Hobby;
use App\Entity\Job;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class PersonneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('age')
            ->add('createdAt')
            ->add('updateAt')
            ->add('profile', EntityType::class, [
                'expanded' => false,
                'required' => false,
                'class' => Profile::class,
                'multiple' => false,
                'attr' => [
                    'class'=>'select2'
                ]
            ])
            ->add('hobbies', EntityType::class, [
                'expanded' => false,
                'required' => false,
                'class' => Hobby::class,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                 return $er->createQueryBuilder('h')
                          ->orderBy('h.designation', 'ASC');
               },
                'choice_label' => 'designation',
                'attr' => [
                    'class'=>'select2'
                ]      
            ])
            ->add('job', EntityType::class, [
                'required' => false,
                'class' => Job::class,
                    'attr' => [
                          'class'=>'select2'
                ]
                
            ])
            //debut ajout code image
            ->add('photo', FileType::class, [
                'label' => 'Votre photo de profil (Des fichiers images uniquement)',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/gif',
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])          
           //fin ajout code image
            ->add('Editer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Personne::class,
        ]);
    }
}
