<?php

namespace App\Form\DataMapper;

use Symfony\Component\Form\DataMapperInterface;

class OrderFormDataMapper implements DataMapperInterface
{
    public function mapDataToForms(mixed $viewData, \Traversable $forms)
    {
        dump($viewData);
    }

    public function mapFormsToData(\Traversable $forms, mixed &$viewData)
    {
        dump($forms);
    }
}