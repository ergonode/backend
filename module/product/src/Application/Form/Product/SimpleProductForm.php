<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Form\Product;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ergonode\Product\Application\Model\Product\SimpleProductFormModel;
use Ergonode\Category\Application\Form\Type\CategoryType;
use Ergonode\Product\Domain\Entity\SimpleProduct;

/**
 */
class SimpleProductForm extends AbstractProductForm
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return SimpleProduct::TYPE === $type;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SimpleProductFormModel::class,
            'translation_domain' => 'product',
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    protected function extendForm(FormBuilderInterface $builder, array $options): void
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
}
