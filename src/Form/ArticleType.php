<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Name:',
                'attr' => [
                    'placeholder' => 'Enter the name for the Article',
                    'class' => 'custom_class',
                ], ])
            ->add('text', TextareaType::class, [
                'label' => 'Text:',
                'attr' => [
                    'placeholder' => 'Enter some text here',
                    'class' => 'text_class',
                ], ])
            ->add('tags',CollectionType::class,[
                'entry_type' => TagType::class,
                'attr' => [
                    'label' => false,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
