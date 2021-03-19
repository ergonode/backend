<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\TranslationDeepl\Application\Form;

use Ergonode\Core\Application\Form\Type\LanguageActiveType;
use Ergonode\TranslationDeepl\Application\Model\Form\TranslationDeeplFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationDeeplForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'content',
                TextareaType::class
            )
            ->add(
                'source_language',
                LanguageActiveType::class,
                [
                    'property_path' => 'sourceLanguage',
                ]
            )
            ->add(
                'target_language',
                LanguageActiveType::class,
                [
                    'property_path' => 'targetLanguage',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TranslationDeeplFormModel::class,
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
