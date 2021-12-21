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

class EditprofilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('edit_email', EmailType::class,[
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => ''
                ],
                'constraints' => new Length(255, 10),
                'attr' => [
                    'placeholder' => 'Laissez ce champ vide, si vous ne voulez pas modifier votre e-mail...'
                ]
            ])
            /**->add('confirm_password', RepeatedType::class,[
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe ne correspond pas.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répetez le mot de passe'],
                'constraints' => new Length(255, 8),
                'attr' => [
                    'placeholder' => 'Laissez ce champ vide, si vous ne voulez pas modifier votre mot de passe...',
                    'class' => '',
                ]
            ]) */
            ->add('client', ClientType::class,[
                'row_attr' => [
                    'id' => 'register-client'                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
