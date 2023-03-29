<?php

namespace App\Form\DataMapper;

use App\Entity\Operation;
use App\Entity\Product;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class OrderFormTypeDataMapper implements DataMapperInterface
{
    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        if (!$viewData) {
            return;
        }
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        /** @var Product $product */
        $product = $forms['product']->getData();
        /** @var string $taxNumber */
        $taxNumber = $forms['taxNumber']->getData();
        try {
            $viewData = new Operation($taxNumber, $product->getPrice());
        } catch (\DomainException|\InvalidArgumentException $e) {
            $forms['taxNumber']->addError(new FormError($e->getMessage()));

            return;
        }
    }
}
