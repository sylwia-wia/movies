<?php

namespace App\Form;

use App\Dto\MovieFilterDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->setMethod('GET')
            ->add('search', TextType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'Szukaj',
                    'class' => 'border-2 text-lg border-gray-200 bg-white h-16 px-5 pr-8 rounded focus:outline-none float-left ml-20'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtruj',
                'attr' => [
                    'class' => 'absolute right-0 top-0 mt-5 mr-4'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MovieFilterDto::class,
        ]);
    }
}
