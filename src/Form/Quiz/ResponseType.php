<?php

namespace App\Form\Quiz;

use App\Entity\Quiz\Response;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ResponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Réponse'])
            ->add('responses', TextareaType::class, [
                'label' => 'Valeurs autorisées',
                'help' => 'Permet d\'indiquer toutes les réponses acceptées séparées par des \';\'.<br/>
                    Les accents ne sont pas pris en compte lors des comparaisons.',
            ])
            ->add('trick', TextType::class, [
                'label' => 'Indice',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Response::class,
        ]);
    }
}
