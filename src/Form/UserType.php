<?php

namespace App\Form;

use App\Entity\Media;
use App\Entity\Product;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('media', FileType::class, [
                'label' => false,
                'mapped' => false,
                'row_attr' => [
                    'class' => "m-0"
                ],
                'attr' => [
                    'class' => "",
                    'placeholder' => ""
                ],
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/jpg'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => false,
                'disabled' => true,
                'attr' => [
                    'class' => "w-4/6 rounded-xl",
                    'placeholder' => "Email"
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => "w-4/6 rounded-xl",
                    'placeholder' => "Prénom"
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'required' => true,
                'attr' => [
                    'class' => "w-4/6 rounded-xl",
                    'placeholder' => "Nom"
                ]
            ])
            ->add('currentPassword', PasswordType::class, [
                'mapped' => false,
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => "w-4/6 rounded-xl",
                    'placeholder' => "Ancien mot de passe"
                ]
            ])
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => false,
                'first_options'  => [
                    'label' => false,
                    'attr' => [
                        'class' => "w-4/6 rounded-xl",
                        'placeholder' => "Nouveau mot de passe"
                    ]
                ],
                'second_options' => [
                    'label' => false,
                    'attr' => [
                        'class' => "w-4/6 rounded-xl",
                        'placeholder' => "Confirmer le mot de passe"
                    ]
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
