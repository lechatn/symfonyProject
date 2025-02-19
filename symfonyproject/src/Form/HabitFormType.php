<?php

namespace App\Form;

use App\Entity\Habits;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class HabitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your description should be at least {{ limit }} characters',
                        'max' => 255,
                        'maxMessage' => 'Your description should be at most {{ limit }} characters',
                    ]),
                ],
            ])
            ->add('difficulty', ChoiceType::class, [
                'choices' => [
                    'very easy' => 'very easy',
                    'easy' => 'easy',
                    'medium' => 'medium',
                    'hard' => 'hard',
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a difficulty',
                    ]),
                ],
            ])
            ->add('color', ColorType::class, [
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a color',
                    ]),
                ],
            ])
            ->add('frequency', ChoiceType::class, [
                'choices' => [
                    'daily' => 'daily',
                    'weekly' => 'weekly',
                ],
                'required' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a frequency',
                    ]),
                ],
            ]);

        if ($options['is_group_creator']) {
            $builder->add('isGroupTask', CheckboxType::class, [
                'label'    => 'Is this a group task?',
                'required' => false,
                'mapped' => false,
            ]);
        } else {
            $builder->add('isGroupTask', HiddenType::class, [
                'label'    => 'Is this a group task?',
                'required' => false,
                'mapped' => false,
                'disabled' => true,
            ]);
        }

        $builder->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habits::class,
            'is_group_creator' => false,
        ]);
    }
}
