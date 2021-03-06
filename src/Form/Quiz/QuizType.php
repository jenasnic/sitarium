<?php

namespace App\Form\Quiz;

use App\Entity\Quiz\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    /**
     * @param FormBuilderInterface<Quiz> $builder
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $pictureUrl = null;
        $thumbnailUrl = null;

        if (!empty($options['data'])) {
            /** @var Quiz $quiz */
            $quiz = $options['data'];
            $pictureUrl = $quiz->getPictureUrl();
            $thumbnailUrl = $quiz->getThumbnailUrl();
        }

        $builder
            ->add('name')
            ->add(
                'pictureFile',
                FileType::class,
                [
                    'required' => false,
                    'help' => 'form.quiz.edit.help.pictureFile',
                    'help_translation_parameters' => ['%name%' => $pictureUrl],
                ]
            )
            ->add(
                'thumbnailFile',
                FileType::class,
                [
                    'required' => false,
                    'help' => 'form.quiz.edit.help.thumbnailFile',
                    'help_translation_parameters' => ['%name%' => $thumbnailUrl],
                ]
            )
            ->add('published', CheckboxType::class, ['required' => false])
            ->add(
                'displayResponse',
                CheckboxType::class,
                [
                    'required' => false,
                    'help' => 'form.quiz.edit.help.displayResponse',
                ]
            )
            ->add(
                'displayTrick',
                CheckboxType::class,
                [
                    'required' => false,
                    'help' => 'form.quiz.edit.help.displayTrick',
                ]
            )
            ->add('pictureUrl', HiddenType::class)
            ->add('thumbnailUrl', HiddenType::class)
            ->add('rank', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            'label_format' => 'form.quiz.edit.label.%name%',
        ]);
    }
}
