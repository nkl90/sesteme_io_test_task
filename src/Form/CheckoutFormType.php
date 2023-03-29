<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CheckoutFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paymentMethod', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Credit Card' => 'cc',
                    'PayPal' => 'pp',
                ],
            ])
            ->add('cardNumber', null, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^4[0-9]{12}(?:[0-9]{3})?$/',
                        'message' => 'Credit card number must be in format 0000 0000 0000 0000',
                    ])
                ]
            ])
            ->add('expiration', null, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/',
                        'message' => 'Expiration date must be in format MM/YYYY',
                    ])
                ]
            ])
            ->add('cvv', null, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[0-9]{3,4}$/',
                        'message' => 'CVV must be in format 000',
                    ])
                ]
            ])
            ->add('ownerName', null, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^[a-zA-Z ]*$/',
                        'message' => 'Owner name must be in format John Doe',
                    ])
                ]
            ])
            ->add('checkout', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
