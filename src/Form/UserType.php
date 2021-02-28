<?php

namespace App\Form;

use App\Entity\User;
use App\Enum\User\RoleEnum;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AccountType
{
    /**
     * @param FormBuilderInterface<User> $builder
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('username', null, [
                'help' => 'global.play',
                'invalid_message' => 'global.play',
            ])
            ->add(
                'roles',
                ChoiceType::class,
                [
                    'choices' => [
                        'form.account.edit.label.roles.admin' => RoleEnum::ROLE_ADMIN,
                        'form.account.edit.label.roles.user' => RoleEnum::ROLE_USER,
                    ],
                    'multiple' => true,
                    'expanded' => false,
                ]
            )
        ;
    }
}
