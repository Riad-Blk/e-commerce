<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ClientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('edit_email', EmailType::class, [
            'label' => $options['label_email'],
            'attr' => [
                'placeholder' => $options['placeholder'],
            ],
            'required' => $options['required'],
        ]);

        if ($options['confirm_password'] !== true) {
            $builder
                ->add('confirm_password', PasswordType::class, [
                    'label' => $options['label_password'],
                    'attr' => [
                        'placeholder' => $options['placeholder'],
                    ]
                ]);
        } else {
            $builder
                ->add('confirm_password', RepeatedType::class,[
                    'type' => PasswordType::class,
                    'invalid_message' => 'Le mot de passe ne correspond pas.',
                    'options' => ['attr' => ['class' => 'password-field']],
                    'required' => true,
                    'first_options' => [
                        'label' => $options['label_password'],
                        'attr' => [
                            'placeholder' => $options['placeholder'],
                            'class' => '',
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Répetez le mot de passe', 
                    ],
                    'constraints' => new Length(255, 8),
                    'required' => $options['required'],
                ])
            ;
        }

        $builder  
            ->add('client', ClientType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'label_email' => null,
            'label_password' => null,
            'placeholder' => null,
            'confirm_password' => null,
            'required' => null,
        ]);
    }
}
