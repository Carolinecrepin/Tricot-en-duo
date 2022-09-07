<?php

namespace App\Form;

use App\Entity\Carrier;
use App\Entity\Address;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CheckoutType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('address', EntityType::class,[
                'class' => Address::class,              //renseigner l'adresse de livraison
                'required' => true,                     // adresse obligatoire oui
                'choices' => $user->getAddresses(),     //possibilité de choisir l'adresse recup adresse
                'multiple' =>false,     //choix multiple non
                'expanded' =>true,      //checkbox oui
            ])
            ->add('carrier', EntityType::class, [
                'class' => Carrier::class,              
                'required' => true,                      
                'multiple' =>false,     
                'expanded' =>true,      
            ])
            ->add('informations', TextareaType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user' => 'string',
        ]);
    }
}
