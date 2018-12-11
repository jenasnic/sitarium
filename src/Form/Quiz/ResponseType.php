<?php

namespace App\Form\Quiz;

use App\Entity\Quiz\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Réponse'])
            ->add(
                'responses',
                TextareaType::class,
                [
                    'label' => 'Valeurs autorisées',
                    'help' => 'Permet d\'indiquer toutes les réponses acceptées séparées par des \';\'.<br/>
                        Les accents ne sont pas pris en compte lors des comparaisons.',
                ]
            )
            ->add(
                'trick',
                TextType::class,
                [
                    'label' => 'Indice',
                    'required' => false,
                ]
            )
            ->add(
                'positionX',
                TextType::class,
                [
                    'label' => 'Position X',
                    'required' => false,
                ]
            )
            ->add(
                'positionY',
                TextType::class,
                [
                    'label' => 'Position Y',
                    'required' => false,
                ]
            )
            ->add(
                'size',
                ChoiceType::class,
                [
                    'label' => 'Taille',
                    'choices' => [
                        'petit' => 1,
                        'moyen' => 2,
                        'grand' => 3,
                    ],
                ]
             )
            ->add(
                'tmdbId',
                TextType::class,
                [
                    'label' => 'Identifiant TMDB',
                    'required' => false,
                ]
            )
            ->add(
                'tagline',
                TextType::class,
                [
                    'label' => 'Slogan',
                    'required' => false,
                ]
            )
            ->add(
                'overview',
                TextType::class,
                [
                    'label' => 'Résumé',
                    'required' => false,
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
        ]);
    }
}
