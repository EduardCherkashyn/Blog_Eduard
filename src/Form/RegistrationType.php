<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('plainpassword', PasswordType::class, [
                'attr'=>[
                    'placeholder'=>'Enter your password',
                    'class'=>'custom_class'
            ]])
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
