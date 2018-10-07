<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\User\Roles;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\Length;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, ['label' => 'Nom', 'required' => false])
            ->add('lastname', TextType::class, ['label' => 'Prénom', 'required' => false])
            ->add('email', TextType::class, ['label' => 'Email'])
            ->add('username', TextType::class, ['label' => 'Login'])
            ->add(
                'newPassword',
                RepeatedType::class, [
                    'mapped' => false,
                    'required' => false,
                    'type' => PasswordType::class,
                    'constraints' => new Length(['min' => 6]),
                    'invalid_message' => 'La confirmation du mot de passe n\'est pas correcte.',
                    'first_options'  => ['label' => 'Mot de passe'],
                    'second_options' => ['label' => 'Confirmation mot de passe'],
                ]
            )
            ->add(
                'roles',
                ChoiceType::class, [
                    'choices' => [
                        'Administrateur' => Roles::ROLE_ADMIN,
                        'Utilisateur' => Roles::ROLE_USER,
                    ],
                    'multiple' => true,
                    'expanded' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
