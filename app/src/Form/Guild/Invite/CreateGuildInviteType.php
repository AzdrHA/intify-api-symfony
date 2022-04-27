<?php

namespace App\Form\Guild\Invite;

use App\Entity\Guild\Guild;
use App\Entity\Guild\GuildInvite;
use App\Form\DefaultForm;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;

class CreateGuildInviteType extends DefaultForm
{
    protected array $defaults = [
        'data_class' => GuildInvite::class
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('guild', EntityType::class, [
                'class' => Guild::class,
                'required' => true
            ])
        ;
    }
}