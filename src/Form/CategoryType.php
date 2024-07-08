<?php

namespace App\Form;

use App\Entity\Categories;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', textType::class, [
                'label' => 'category.name',
                'attr' => [
                    'placeholder' => 'category.name.placeholder',
                ],
            ])
            ->add('description', CKEditorType::class, [
                'label' => 'category.description',
                'config_name' => 'shortDescription_config',
                'attr' => [
                    'placeholder' => 'category.description.placeholder',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
            'translation_domain' => 'form',
        ]);
    }
}
