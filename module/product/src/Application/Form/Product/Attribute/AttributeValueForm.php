<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\Attribute;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\Product\Application\Model\Product\Attribute\AttributeValueFormModel;

class AttributeValueForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add(
            'values',
            CollectionType::class,
            [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => AttributeValueTranslationForm::class
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttributeValueFormModel::class,
            'translation_domain' => 'product',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
