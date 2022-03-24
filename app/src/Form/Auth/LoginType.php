<?php

namespace App\Form\Auth;

use App\Entity\User\User;
use App\Form\DefaultForm;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginType extends DefaultForm
{
    protected array $defaults = [
        'data_class' => User::class
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'required' => true
            ])
            ->add('password', PasswordType::class, [
                'required' => true
            ])
        ;
    }
}