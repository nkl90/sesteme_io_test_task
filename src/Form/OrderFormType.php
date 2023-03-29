<?php

namespace App\Form;

use App\Entity\Operation;
use App\Entity\Product;
use App\Enum\TaxByCountryEnum;
use App\Form\DataMapper\OrderFormTypeDataMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class OrderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'mapped' => false,
                'class' => Product::class,
                'choice_label' => function ($product) {
                    return $product->getName().' ('.$product->getPrice().')';
                },
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('taxNumber', null, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => TaxByCountryEnum::getRegex(),
                        'message' => 'Tax number must be in format XX0000000000',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Order',
            ])
            ->setDataMapper(new OrderFormTypeDataMapper())
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Operation::class,
            'empty_data' => null,
        ]);
    }
}
