<?php

namespace App\Form;

use App\Entity\Resource;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ResourceType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.name'
            ])
            ->add('tags', EntityType::class, [
                'label' => 'form.tags',
                'class' => Tag::class,
                'multiple' => true,
                'expanded' => true
            ])
            ->add('file', FileType::class, [
                'label' => 'form.upload',
                'mapped' => false,
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '200m',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'application'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PDF document',

                    ])
                ],
            ])
            ->add('folder', HiddenType::class, [
                'required' => false,
                'mapped' => false
            ])
        ->add('save', SubmitType::class, [
            'label' => 'form.submit'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Resource::class,
        ]);
    }
}
