<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ClientType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => new Length(255, 10),
                'attr' => [
                    'placeholder' => 'Veuillez saisir une adresse e-mail valide!'
                ]
            ])

            ->add('password', PasswordType::class,[
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => ''
                ],
                'help' => 'Les mots de passes doivent avoir au minimum 8 caractères',
                'constraints' => new Length(255, 8),
                'attr' => [
                    'placeholder' => 'Veuillez saisir votre mot de passe...',
                    'class' => '',
                ]
            ])
            ->add('confirm_password', PasswordType::class,[
                'label' => 'Confirmer votre mot de passe',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => new Length(255, 8),
                'attr' => [
                    'placeholder' => 'Veuillez confirmer votre mot de passe...',
                    'class' => '',
                ]
            ])
            ->add('client', ClientType::class,[
                'row_attr' => [
                    'id' => 'register-client'                ]
            ])
            ->add('termsAccepted', CheckboxType::class,  array(
                'mapped' => false,
                'constraints' => new IsTrue(),
            ))
            ->add('termsAccepted', CheckboxType::class,  array(
                'mapped' => false,
                'constraints' => new IsTrue(),
            )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
