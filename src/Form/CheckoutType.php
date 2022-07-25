<?php

namespace App\Form;

use App\Entity\Carrier;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //$user = $options['user'];

        $builder
            ->add('address', EntityType::class,[
                'class' => Address::class,              //renseigner l'adresse de livraison
                'required' => true,                     // adresse obligatoire oui
                'choices' => User::class,     //possibilité de choisir l'adresse recup adresse //'choices' => $user->getAddresses()
                'multiple' =>false,     //choix multiple non
                'expended' =>true,      //checkbox oui
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,              
                'required' => true,                      
                'multiple' =>false,     
                'expended' =>true,      
            ])
            ->add('informations')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
