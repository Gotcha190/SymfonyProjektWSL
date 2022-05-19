<?php

namespace App\Form;

use App\Entity\{Comment, User};
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\{AbstractType,
    Extension\Core\Type\HiddenType,
    Extension\Core\Type\SubmitType,
    FormBuilderInterface
};
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('save', SubmitType::class, ['label' => 'Save comment']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
