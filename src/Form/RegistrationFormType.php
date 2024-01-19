<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Email',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700  focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner une adresse mail',
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Prénom',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700  focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un prénom',
                    ])
                ],
            ])
            ->add('lastname', TextType::class, [
                'label' => false,
                'attr' => [
                    'placeholder'=> 'Nom',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700  focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner un nom',
                    ])
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'label' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder'=> 'Mot de passe',
                    'class' => "block w-full px-4 py-2 mt-2 text-gray-700  focus:ring-opacity-40 focus:outline-none focus:ring focus:ring-blue-300"
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
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
