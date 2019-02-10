<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('lastname')
            ->add('firstname')
            ->add(
                'newPassword',
                RepeatedType::class,
                [
                    'mapped' => false,
                    'required' => false,
                    'type' => PasswordType::class,
                    'constraints' => new Length(['min' => 3]),
                    'invalid_message' => 'form.account.edit.error.password',
                    'label_format' => 'form.account.edit.label.password.%name%',
                ]
            )
        ;

        if (!$options['ignore_email']) {
            $builder->add('email');
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'ignore_email' => false,
            'label_format' => 'form.account.edit.label.%name%',
        ]);
    }
}
