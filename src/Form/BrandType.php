<?php

namespace App\Form;

use App\Entity\Brand;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class BrandType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('image')
            ->add('rating')
            ->add('countryCode', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 2,
                        'exactMessage' => 'Country code must be exactly 2 characters long'
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-z]{2}$/',
                        'message' => 'Country code must be exactly 2 letters (e.g., BG, DE)'
                    ])
                ],
                'attr' => [
                    'placeholder' => 'e.g., BG, DE',
                    'maxlength' => 2,
                    'style' => 'text-transform: uppercase;'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Brand::class,
        ]);
    }
}
