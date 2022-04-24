<?php

namespace App\Form\Message;

use App\Entity\Message\Message;
use App\Form\DefaultForm;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MessageCreateType extends DefaultForm
{
    protected array $defaults = [
        'data_class' => Message::class
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextareaType::class, [
                'required' => true
            ])
            ->add('file', FileType::class, [
                'mapped' => false
            ])
        ;
    }
}