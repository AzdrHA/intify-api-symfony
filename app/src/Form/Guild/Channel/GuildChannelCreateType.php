<?php

namespace App\Form\Guild\Channel;

use App\Entity\Channel\Channel;
use App\Form\DefaultForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class GuildChannelCreateType extends DefaultForm
{
    protected array $defaults = [
        'data_class' => Channel::class
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices' => Channel::CHANNEL_TYPES
            ])
            ->add('name', TextType::class, [
                'required' => true,
            ])
            ->add('topic', TextareaType::class, [
                'required' => true,
            ])
            ->add('parent', EntityType::class, [
                'class' => Channel::class,
                'required' => false
            ])
        ;
    }
}