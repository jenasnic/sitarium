<?php

namespace App\Form\Quiz;

use App\Entity\Quiz\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
            ->add('name', TextType::class, ['label' => 'Nom du quiz'])
            ->add(
                'pictureFile',
                FileType::class,
                [
                    'required' => false,
                    'label' => 'Image du quiz',
                    'help' => $helpPictureFile,
                ]
            )
            ->add(
                'thumbnailFile',
                FileType::class,
                [
                    'required' => false,
                    'label' => 'Vignette',
                    'help' => $helpThumbnailFile,
                ]
            )
            ->add(
                'published',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Publication',
                ]
            )
            ->add(
                'displayResponse',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Afficher les réponses',
                    'help' => 'Permet de visualiser sur l\'image les éléments du quiz relatifs aux réponses trouvées.<br/>
                        Si cette option est activée, les réponses doivents définir les coordonées permettant de localiser les éléments associés.',
                ]
            )
            ->add(
                'displayTrick',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Autoriser les indices',
                    'help' => 'Permet de donner un indice sur les films à trouver.<br/>
                        Si cette option est activée, les réponses doivents définir les coordonées permettant de localiser les éléments associés.',
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
        ]);
    }
}
