<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Form\Type;

use Ergonode\Product\Application\Form\Transformer\SkuDataTransformer;
use Ergonode\Product\Domain\Query\ProductQueryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 */
class SkuType extends AbstractType
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
        $builder->addModelTransformer(new SkuDataTransformer());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $skus = $this->query->getAllSkus();
        $choices = array_combine($skus, $skus);

        $resolver->setDefaults(
            [
                'choices' => array_flip($choices),
                'invalid_message' => 'Sku not exists',
                'multiple' => false,
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
