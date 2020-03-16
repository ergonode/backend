<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Factory\Decorator;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

/**
 */
class StatusProductFactoryDecorator implements ProductFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private ProductFactoryInterface $factory;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $provider;

    /**
     * @param ProductFactoryInterface $factory
     * @param WorkflowProvider        $provider
     */
    public function __construct(ProductFactoryInterface $factory, WorkflowProvider $provider)
    {
        $this->factory = $factory;
        $this->provider = $provider;
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    public function isSupportedBy(string $type): bool
    {
        return $this->factory->isSupportedBy($type);
    }

    /**
     * @param ProductId $id
     * @param Sku       $sku
     * @param array     $categories
     * @param array     $attributes
     *
     * @return AbstractProduct
     *
     * @throws \Exception
     */
    public function create(
        ProductId $id,
        Sku $sku,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        $workflow = $this->provider->provide();
        $attributes[StatusSystemAttribute::CODE] = new StringValue($workflow->getDefaultStatus()->getValue());

        return $this->factory->create($id, $sku, $categories, $attributes);
    }
}
