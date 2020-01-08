<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', null, [
                'attr'=> [
                    'required' => true,
                ]
            ])
            ->add('title', null, [
                'attr'=> [
                    'required' => true,
                ]
            ])
            ->add('content', null, [
                'attr'=> [
                    'required' => true,
                ]
            ])
            ->add('keywords', null, [
                'attr'=> [
                    'required' => true,
                ]
            ])
            ->add('description', null, [
                'attr'=> [
                    'required' => true,
                ]
            ])
            ->add('status')
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
