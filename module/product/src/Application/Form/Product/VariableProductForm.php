<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Form\Product;

use Ergonode\Category\Application\Form\Type\CategoryType;
use Ergonode\Product\Application\Model\Product\VariableProductFormModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Product\Domain\Entity\VariableProduct;

class VariableProductForm extends AbstractType implements ProductFormInterface
{
    public function supported(string $type): bool
    {
        return VariableProduct::TYPE === $type;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'sku',
                TextType::class
            )
            ->add(
                'templateId',
                TextType::class,
                [
                    'property_path' => 'template',
                ]
            )
            ->add(
                'categoryIds',
                CategoryType::class,
                [
                    'property_path' => 'categories',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VariableProductFormModel::class,
            'translation_domain' => 'product',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): ?string
    {
        return null;
    }
}
