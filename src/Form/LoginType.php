<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                'placeholder' => 'Email',
                'class' => 'custom_class form-control',
                'id' => 'inputEmail',
                'required' => true,
                'autofocus' => true,
            ], ])
            ->add('password', PasswordType::class, [
            'attr' => [
                'placeholder' => 'Password',
                'class' => 'custom_class form-control',
                'id' => 'inputPassword',
                'required' => true,
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => ['novalidate' => 'novalidate'],
            'data_class' => User::class,
            'csrf_protection' => true,
            'csrf_field_name' => 'csrf_token',
            'csrf_token_id' => 'authenticate',
        ]);
    }
}
