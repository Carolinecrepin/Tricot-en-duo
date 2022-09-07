<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fullname',TextType::class)
            ->add('company',TextType::class, [
                'required' => false,
            ])
            ->add('address',TextType::class)
            ->add('complement',TextType::class, [
                'required' => false,
            ])
            ->add('phone',TextType::class)
            ->add('city',TextType::class)
            ->add('codePostal',TextType::class)
            ->add('country', CountryType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
