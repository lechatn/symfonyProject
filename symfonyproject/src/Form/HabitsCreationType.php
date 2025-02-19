<?php

namespace App\Form;

use App\Entity\Habits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class HabitsCreationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a description',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your description should be at least {{ limit }} characters',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('difficulty', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a difficulty',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your difficulty should be at least {{ limit }} characters',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('color', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a color',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your color should be at least {{ limit }} characters',
                        'max' => 50,
                    ]),
                ],
            ])
            ->add('frequency', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a frequency',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Your frequency should be at least {{ limit }} characters',
                        'max' => 50,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Habits::class,
        ]);
    }
}
