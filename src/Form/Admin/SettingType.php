<?php

namespace App\Form\Admin;

use App\Entity\Admin\Setting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Title')
            ->add('Description')
            ->add('Keywords')
            ->add('Company')
            ->add('Address')
            ->add('Phone')
            ->add('Email')
            ->add('Facebook')
            ->add('Instagram')
            ->add('Twitter')
            ->add('Youtube')
            ->add('Smtpserver')
            ->add('Smtpmail')
            ->add('Smtppass')
            ->add('Smtpport')
            ->add('About')
            ->add('Contact')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
