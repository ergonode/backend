<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Attribute;

use Ergonode\Attribute\Application\Model\Attribute\AttributeFormModel;
use Ergonode\Core\Application\Form\Type\TranslationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Ergonode\Product\Domain\Entity\Attribute\ProductRelationAttribute;
use Ergonode\Attribute\Application\Form\Attribute\AttributeFormInterface;

class ProductRelationAttributeForm extends AbstractType implements AttributeFormInterface
{
    public function supported(string $type): bool
    {
        return ProductRelationAttribute::TYPE === $type;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'code',
                TextType::class
            )
            ->add(
                'label',
                TranslationType::class
            )
            ->add(
                'hint',
                TranslationType::class
            )
            ->add(
                'placeholder',
                TranslationType::class
            )
            ->add(
                'groups',
                CollectionType::class,
                [
                    'allow_add' => true,
                    'allow_delete' => true,
                    'entry_type' => TextType::class,
                ]
            )
            ->add(
                'scope',
                TextType::class,
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AttributeFormModel::class,
            'translation_domain' => 'attribute',
            'allow_extra_fields' => true,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
