<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'attr'=>[
                    'placeholder'=>'Enter your email',
                    'class'=>'custom_class'
                ]])
            ->add('name', TextType::class, [
                'attr'=>[
                    'placeholder'=>'Enter your name',
                    'class'=>'custom_class'
                ]])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array('class' => 'password-field',)),
                'required' => true,
                'first_options'  => array('label' => 'Password','attr'=>[
                    'placeholder'=>'Enter your password',
                    'class'=>'custom_class'
                ]),
                'second_options' => array('label' => 'Repeat Password','attr'=>[
                    'placeholder'=>'Repeat password',
                    'class'=>'custom_class'
                ]),
            ))
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'attr' => ['novalidate' => 'novalidate']
        ]);
    }
}
