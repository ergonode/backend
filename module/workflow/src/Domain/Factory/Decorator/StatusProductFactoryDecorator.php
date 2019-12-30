<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Factory\Decorator;

use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\StringValue;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;

/**
 */
class StatusProductFactoryDecorator implements ProductFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private $factory;

    /**
     * @var WorkflowRepositoryInterface
     */
    private $repository;

    /**
     * @param ProductFactoryInterface     $factory
     * @param WorkflowRepositoryInterface $repository
     */
    public function __construct(ProductFactoryInterface $factory, WorkflowRepositoryInterface $repository)
    {
        $this->factory = $factory;
        $this->repository = $repository;
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
        $workflow = $this->repository->load(WorkflowId::fromCode(Workflow::DEFAULT));
        if ($workflow && $workflow->hasDefaultStatus()) {
            $attributes[StatusSystemAttribute::CODE] = new StringValue($workflow->getDefaultStatus()->getValue());
        }

        return $this->factory->create($id, $sku, $categories, $attributes);
    }
}
