<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Categories;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('metaTitle', textType::class, [
                'label' => 'article.metaTitle',
                'attr' => [
                    'placeholder' => 'article.metaTitle.placeholder',
                ],
            ])
            ->add('slug', textType::class, [
                'label' => 'article.slug',
                'attr' => [
                    'placeholder' => 'article.slug.placeholder',
                ],
            ])
            ->add('title', textType::class, [
                'label' => 'article.title',
                'attr' => [
                    'placeholder' => 'article.title.placeholder',
                ],
            ])
            ->add('shortDescription', CKEditorType::class, [
                'label' => 'article.shortDescription',
                'config_name' => 'shortDescription_config',
            ])
            ->add('summary', CKEditorType::class, [
                'label' => 'article.summary',
                'config_name' => 'shortDescription_config',
            ])
            ->add('content', CKEditorType::class, [
                'label' => 'article.content',
                'config_name' => 'content_config',
            ])
            ->add('mainPicture', ElFinderType::class,
                [
                    'instance' => 'form', 'enable' => true,
                    'label' => 'article.image',
                    'attr' => [
                        'placeholder' => 'article.image.placeholder',
                    ],
                ])
            ->add('mainPictureCaption', TextareaType::class, [
                'label' => 'article.mainPictureCaption',
                'required' => false,
                'attr' => [
                    'placeholder' => 'article.mainPictureCaption.placeholder',
                ],
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'multiple' => true,
                'label' => 'article.category.name.placeholder',
                'attr' => [
                    'class' => 'multiSelect',
                    'data-placeholder' => 'article.category.name.placeholder',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'article.draft' => Articles::STATUS_DRAFT,
                    'article.published' => Articles::STATUS_PUBLISHED,
                ],
                'multiple' => false,
                'label' => 'article.status',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
            'translation_domain' => 'form',
        ]);
    }
}
