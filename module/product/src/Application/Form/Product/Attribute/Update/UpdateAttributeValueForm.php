<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product\Attribute\Update;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ergonode\Product\Application\Model\Product\Attribute\Update\UpdateAttributeValueFormModel;

class UpdateAttributeValueForm extends AbstractType
{
    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'id',
                TextType::class,
            )
            ->add(
                'values',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => UpdateAttributeValueTranslationForm::class,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateAttributeValueFormModel::class,
            'translation_domain' => 'product',
        ]);
    }

    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
