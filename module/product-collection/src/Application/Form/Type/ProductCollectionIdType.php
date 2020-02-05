<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Form\Type;

use Ergonode\ProductCollection\Application\Form\Transformer\ProductCollectionIdDataTransformer;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class ProductCollectionIdType extends AbstractType
{
    /**
     * @var ProductCollectionQueryInterface
     */
    private ProductCollectionQueryInterface $query;

    /**
     * @param ProductCollectionQueryInterface $query
     */
    public function __construct(ProductCollectionQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new ProductCollectionIdDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $collections = $this->query->getDictionary();
        $resolver->setDefaults(
            [
                'choices' => array_flip($collections),
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
