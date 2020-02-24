<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Form\Type;

use Ergonode\Product\Application\Form\Transformer\ProductIdDataTransformer;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ProductIdType extends AbstractType
{
    /**
     * @var ProductQueryInterface
     */
    private ProductQueryInterface $query;

    /**
     * @param ProductQueryInterface $query
     */
    public function __construct(ProductQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new ProductIdDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $products = $this->query->getDictionary();
        $resolver->setDefaults(
            [
                'choices' => array_flip($products),
                'invalid_message' => 'Type is not valid',
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
