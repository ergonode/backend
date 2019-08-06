<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Form\Type;


use Ergonode\TranslationDeepl\Application\Model\Form\Type\TranslationDeeplConfigurationTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationDeeplConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('target_lang',
                TextType::class
            )->add('source_lang',
                TextType::class
            )->add('tag_handling',
                CollectionType::class
            )->add('non_splitting_tags',
                CollectionType::class
            )->add('ignore_tags',
                CollectionType::class
            )->add('split_sentences',
                TextType::class
            )->add('preserve_formatting',
                TextType::class
            );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TranslationDeeplConfigurationTypeModel::class,
            'translation_domain' => 'translation_deepl',
        ]);
    }
}
