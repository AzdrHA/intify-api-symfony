<?php

namespace App\Form\User\Channel;

use App\Entity\Channel\Channel;
use App\Entity\User\User;
use App\Form\DefaultForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class UserPrivateChannelCreateType extends DefaultForm
{
    protected array $defaults = [
        'data_class' => Channel::class
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipients', EntityType::class, [
                'class' => User::class,
                'multiple' => true,
                'required' => true,
            ])
        ;
    }
}