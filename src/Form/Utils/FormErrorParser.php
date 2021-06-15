<?php

declare(strict_types=1);

namespace App\Form\Utils;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormErrorParser
{
    public static function arrayParse(FormInterface $form): array
    {
        $formName = $form->getName();
        $errors   = [];

        /** @var FormError $formError */
        foreach ($form->getErrors(true, true) as $formError) {
            $name      = '';
            $thisField = $formError->getOrigin()->getName();
            $origin    = $formError->getOrigin();
            while ($origin = $origin->getParent()) {
                if ($formName !== $origin->getName()) {
                    $name = $origin->getName().'_'.$name;
                }
            }
            $fieldName = $name.$thisField;
            $errors[$fieldName][] = $formError->getMessage();
        }

        return $errors;
    }
}
