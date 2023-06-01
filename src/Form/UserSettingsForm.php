<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserSettingsForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Name',
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Surname',
            ])
            ->add('birthday', DateType::class, [
                'label' => 'Age',
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'datepicker',
                ],
            ])
            ->add('sex', ChoiceType::class, [
                'label' => 'Sex',
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                    'Other' => 'Other',
                ],
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('favoriteLibrary', ChoiceType::class, [
                'label' => 'Favorite Library',
                'choices' => $options['libraries'],
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Select a favorite library',
                'required' => 'false',
            ])
//            ->add('favoriteBooks', TextareaType::class, [
//                'label' => 'Favorite Books',
//                'attr' => [
//                    'rows' => 3,
//                ],
//            ])
//            ->add('profilePicture', FileType::class, [
//                'label' => 'Profile Picture',
//                'required' => false,
//            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'libraries' => [],
        ]);
    }
}
