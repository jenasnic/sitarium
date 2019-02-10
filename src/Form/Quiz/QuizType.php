<?php

namespace App\Form\Quiz;

use App\Entity\Quiz\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $helpPictureFile = null;
        $helpThumbnailFile = null;

        if (!empty($options['data'])) {
            /** @var Quiz $quiz */
            $quiz = $options['data'];

            if (!empty($quiz->getPictureUrl())) {
                $helpPictureFile = 'Image actuellement chargée : '.$quiz->getPictureUrl();
            }

            if (!empty($quiz->getThumbnailUrl())) {
                $helpThumbnailFile = 'Image actuellement chargée : '.$quiz->getThumbnailUrl();
            }
        }

        $builder
            ->add('name')
            ->add(
                'pictureFile',
                FileType::class,
                [
                    'required' => false,
                    'help' => $helpPictureFile,
                ]
            )
            ->add(
                'thumbnailFile',
                FileType::class,
                [
                    'required' => false,
                    'help' => $helpThumbnailFile,
                ]
            )
            ->add('published', CheckboxType::class, ['required' => false])
            ->add(
                'displayResponse',
                CheckboxType::class,
                [
                    'required' => false,
                    'help' => 'form.quiz.edit.help.displayResponse'
                ]
            )
            ->add(
                'displayTrick',
                CheckboxType::class,
                [
                    'required' => false,
                    'help' => 'form.quiz.edit.help.displayTrick'
                ]
            )
            ->add('pictureUrl', HiddenType::class)
            ->add('thumbnailUrl', HiddenType::class)
            ->add('rank', HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            'label_format' => 'form.quiz.edit.label.%name%',
        ]);
    }
}
