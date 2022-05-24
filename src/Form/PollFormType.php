<?php

namespace App\Form;

use App\Entity\Poll;
use App\Entity\PollAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('question');
        $builder->add('pollAnswers', CollectionType::class, [
            'entry_type' => PollAnswersFormType::class,
            'entry_options' => ['label' => false],
            'allow_add' => true,
            'prototype' => true,
            'by_reference' => false,
        ]);
        $builder->add('save', SubmitType::class, ['label' => 'Save poll']);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poll::class,
        ]);
    }
}
