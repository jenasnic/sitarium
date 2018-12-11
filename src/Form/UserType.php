<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\User\Roles;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AccountType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username', TextType::class, ['label' => 'Login'])
            ->add(
                'roles',
                ChoiceType::class,
                [
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
            'ignore_email' => false,
        ]);
    }
}
