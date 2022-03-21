<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefaultForm extends AbstractType
{
    protected array $defaults = [];

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            array_merge([
                'csrf_protection' => false,
                'allow_extra_fields' => true,
            ], $this->defaults)
        );
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return "";
    }
}