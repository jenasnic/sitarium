<?php

namespace App\Form\Quiz;

use App\Entity\Quiz\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResponseType extends AbstractType
{
    /**
     * @param FormBuilderInterface<Response> $builder
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('responses', TextareaType::class, [
                'help' => 'form.quiz.response.help.title',
            ])
            ->add('trick', TextType::class, ['required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
            'label_format' => 'form.quiz.response.edit.label.%name%',
        ]);
    }
}
