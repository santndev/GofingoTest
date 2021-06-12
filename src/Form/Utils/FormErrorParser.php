<?php

namespace App\Form\Utils;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class FormErrorParser
{
    /**
     * @param FormInterface $form
     *
     * @return string[][]
     * @psalm-return array<string, list<string>>
     */
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
            /**
             * One field can have multiple errors
             */
            if (!in_array($fieldName, $errors)) {
                $errors[$fieldName] = [];
            }
            $errors[$fieldName][] = $formError->getMessage();
        }

        return $errors;
    }
}
