<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Form\Type;

use Ergonode\ProductCollection\Application\Form\Transformer\ProductCollectionTypeIdDataTransformer;
use Ergonode\ProductCollection\Domain\Query\ProductCollectionTypeQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCollectionTypeIdType extends AbstractType
{
    private ProductCollectionTypeQueryInterface $query;

    public function __construct(ProductCollectionTypeQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer(new ProductCollectionTypeIdDataTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $collectionTypes = $this->query->getDictionary();
        $resolver->setDefaults(
            [
                'choices' => array_flip($collectionTypes),
                'invalid_message' => 'Type is not valid',
            ]
        );
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
