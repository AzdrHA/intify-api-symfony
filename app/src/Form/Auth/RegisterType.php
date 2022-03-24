<?php

namespace App\Form\Auth;

use App\Entity\User\User;
use App\Form\DefaultForm;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

class RegisterType extends DefaultForm
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
            ->add('firstname', TextType::class, [
                'required' => true
            ])
            ->add('lastname', TextType::class, [
                'required' => true
            ])
            ->add('username', TextType::class, [
                'required' => true
            ])
            ->add('password', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '#^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,255}$#',
                        "htmlPattern" => '^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,255}$',
                        'message' => 'Le mot de passe doit contenir au minimum 8 caractères, une majuscule et un chiffre'
                    ])
                ],
            ])
        ;
    }
}