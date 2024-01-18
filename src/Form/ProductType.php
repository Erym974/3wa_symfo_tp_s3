<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Media;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('medias', FileType::class, [
                'label' => false,
                'required' => false,
                'multiple' => true,
                'mapped' => false,
                'row_attr' => [
                    'class' => "mb-0"
                ],
                'attr' => [
                    'class' => "block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none"
                ],
                'constraints' => [
                    new All([
                        'constraints' => [
                          new File([
                            'maxSize' => '5120k',
                            'mimeTypesMessage' => 'Please upload a valid PDF document',
                            'mimeTypes' => [
                              'image/png',
                              'image/jpg',
                              'image/jpeg',
                            ]
                          ]),
                        ],
                      ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Titre',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un titre',
                    ]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => "block w-full h-32 md:h-64 lg:h-96 px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer une description',
                    ]),
                ],
            ])
            ->add('condition', ChoiceType::class, [
                'choices' => [
                    'Jamais ouvert' => Product::SCEALED,
                    'Comme neuf' => Product::NEW,
                    'Utilisé' => Product::USED,
                    'Abimé' => Product::DAMAGED,
                ],
                'required' => true,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Etat',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un état',
                    ]),
                ],
            ])
            ->add('price', MoneyType::class, [
                'currency' => false,
                'divisor' => 100,
                'label' => false,
                'required' => true,
                'attr' => [
                    'placeholder' => 'Prix',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un prix',
                    ]),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'label' => false,
                'choice_label' => 'title',
                'attr' => [
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-500 border rounded-lg focus:border-blue-400 focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
