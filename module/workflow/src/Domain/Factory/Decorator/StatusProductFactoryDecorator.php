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
use Ergonode\Workflow\Domain\Query\StatusQueryInterface;

/**
 */
class StatusProductFactoryDecorator implements ProductFactoryInterface
{
    /**
     * @var ProductFactoryInterface
     */
    private $factory;

    /**
     * @var StatusQueryInterface
     */
    private $query;

    /**
     * @param ProductFactoryInterface $factory
     * @param StatusQueryInterface    $query
     */
    public function __construct(ProductFactoryInterface $factory, StatusQueryInterface $query)
    {
        $this->factory = $factory;
        $this->query = $query;
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
     * @param ProductId  $id
     * @param Sku        $sku
     * @param TemplateId $templateId
     * @param array      $categories
     * @param array      $attributes
     *
     * @return AbstractProduct
     */
    public function create(ProductId $id, Sku $sku, TemplateId $templateId, array $categories = [], array $attributes = []): AbstractProduct
    {
        $statuses = $this->query->getAllCodes();
        if (!empty($statuses)) {
            $statusCode = reset($statuses);
            $attributes[AbstractProduct::STATUS] = new StringValue($statusCode);
        }

        return $this->factory->create($id, $sku, $templateId, $categories, $attributes);
    }
}
