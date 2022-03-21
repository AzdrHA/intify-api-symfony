<?php

namespace App\Utils;

use Symfony\Component\Form\FormInterface;

class UtilsForm
{
    /**
     * @param FormInterface $form
     * @return array
     */
    static function getErrors(FormInterface $form): array
    {
        return self::getErrorsAsArray($form);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    static function getErrorsAsArray(FormInterface $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $key => $child) {
            if ($err = self::getErrorsAsArray($child)) {
                $errors[$key] = $err;
            }
        }

        return $errors;
    }
}