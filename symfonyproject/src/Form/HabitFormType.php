<?php

namespace App\Form;

use App\Entity\Habits;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Console\Color;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HabitFormType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('description', TextType::class)
                ->add('difficulty', ChoiceType::class, [
                    'choices' => [
                        'very easy' => 'very easy',
                        'easy' => 'easy',
                        'medium' => 'medium',
                        'hard' => 'hard',
                    ],
                ])
                ->add('color', ColorType::class)
                ->add('frequency', ChoiceType::class, [
                    'choices' => [
                        'daily' => 'daily',
                        'weekly' => 'weekly',
                    ],
                ])
                ->add('save', SubmitType::class)
            ;
        }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habits::class,
        ]);
    }
}
