<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class MovieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'bg-transparent block border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Podaj tytuł filmu...',
                ],
                'label' => false,
                'constraints' => [
                    new Length(['min' => 3]),
                    new NotBlank()
                ],
            ])
            ->add('releaseYear', IntegerType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-20 text-6xl outline-none',
                    'placeholder' => 'Podaj rok wydania...'
                ],
                'label' => false,
                'constraints' => [
                    new LessThanOrEqual(date('Y')),
                    new NotBlank()
                ]
            ])
            ->add('category', EntityType::class, [
                'required' => false,
               'class' => Category::class,
                'choice_label' => 'name',
                'label' => false,
                'placeholder' => 'Kategoria...',
                'attr' => [
                    'class' => 'mt-10 bg-transparent border-b-2 text-6xl text-gray-400 h-20 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500'
                ],

            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'bg-transparent block mt-10 border-b-2 w-full h-60 text-6xl outline-none',
                    'placeholder' => 'Podaj opis filmu...'
                ],
                'label' => false
            ])
            ->add('imagePath', FileType::class, [
                'required' => false,
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'block mb-2 text-sm font-medium text-gray-900 dark:text-white'
                ],

               ])

//            ->add('actors')
        ;

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
