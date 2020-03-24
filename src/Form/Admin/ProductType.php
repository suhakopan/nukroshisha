<?php

namespace App\Form\Admin;

use App\Entity\Admin\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title')
            ->add('Keywords')
            ->add('Type')
            ->add('Description')
            ->add('Amount')
            ->add('Pprice')
            ->add('SPrice')
            ->add('Detail')
            ->add('Image1')
            ->add('Image2')
            ->add('Image3')
            ->add('Image4')
            ->add('Image5')
            ->add('status')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'csrf_protection' =>false,
        ]);
    }
}
