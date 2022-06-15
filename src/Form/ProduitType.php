<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          //  ->add('id',TextType::class, array('label'=>'Id du produit ','attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('libelle',TextType::class, array('label'=>'Libellé du produit ','attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('qtestock', TextType::class,array('label'=>'Quanté en stock ','attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('categorie', EntityType::class, [
                'required' => false,
                'class' => Categorie::class,
                    'attr' => [
                          'class'=>'select2'
                ]
                
            ])
            ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary btn-xl form-group')))
  //          ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
