<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Produit;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('qte',TextType::class, array('label'=>'Quanté acheté ','attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('prix',TextType::class, array('label'=>'Prix de sortie','attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('date',DateType::class, array('label'=>"Date d'entrée",'attr'=>array('require'=>'require','class'=>'form-control form-group')))
            ->add('produit')

            ->add('Valider', SubmitType::class, array('attr'=>array('class'=>'btn btn-primary btn-xl form-group'))) 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
