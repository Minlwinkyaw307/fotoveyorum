<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('rate', ChoiceType::class, [
                'choices' =>
                [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ]
            ])
            ->add('comment', TextareaType::class, [
                'attr' =>
                [
                    'class' => 'ckeditor',
                ]
            ])
//            ->add('ip')
            ->add('status', ChoiceType::class, [
                'choices' =>
                [
                    'Show' => 1,
                    'Hidden' => 0,
                ]
            ])
//            ->add('created_at')
//            ->add('updated_at')
//            ->add('post')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
