<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Application\Form;

use Ergonode\Component\Designer\Application\Form\Type\TemplateElementType;
use Ergonode\TranslationDeepl\Application\Form\Type\TranslationDeeplConfigurationType;
use Ergonode\TranslationDeepl\Application\Model\Form\TranslationDeeplFormModel;
use Ergonode\TranslationDeepl\Application\Model\Form\Type\TranslationDeeplConfigurationTypeModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationDeeplForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
$builder
    ->add(
        'text',
        TextareaType::class
    )->add(
        'configuration',
        CollectionType::class,
        [
            'entry_type' => TranslationDeeplConfigurationType::class,
            'allow_add' => true,
            'allow_delete' => true,
        ]
    );
}
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TranslationDeeplFormModel::class,
            'translation_domain' => 'translation_deepl',
        ]);
    }

    /**
     * @return null|string
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
