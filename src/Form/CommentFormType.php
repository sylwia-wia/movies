<?php

namespace App\Form;

use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'attr' => [
                    'class' => 'bg-transparent block border-b-2 w-full h-10 text-2xl outline-none',
                    'placeholder' => 'Dodaj komentarz...',
                ],
                'label' => false,
                'constraints' => [
                    new Length(['min' => 3]),
                ]
            ]);
//            ->add('author')
//            ->add('email')
//            ->add('createdAt')
//            ->add('photoFilename')
//            ->add('movie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
