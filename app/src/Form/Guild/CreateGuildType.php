<?php

namespace App\Form\Guild;

use App\Entity\Guild\Guild;
use App\Form\DefaultForm;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CreateGuildType extends DefaultForm
{
    protected array $defaults = [
        'data_class' => Guild::class
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
        ;
    }
}