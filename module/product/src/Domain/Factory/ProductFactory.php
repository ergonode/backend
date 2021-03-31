<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Factory;

use Ergonode\Core\Application\Security\Security;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Product\Infrastructure\Provider\ProductStrategyProvider;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Value\Domain\ValueObject\StringValue;

class ProductFactory implements ProductFactoryInterface
{
    private ProductStrategyProvider $productProvider;

    private Security $security;

    public function __construct(ProductStrategyProvider $productProvider, Security $security)
    {
        $this->productProvider = $productProvider;
        $this->security = $security;
    }

    public function create(
        string $type,
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories = [],
        array $attributes = []
    ): AbstractProduct {
        $attributes = $this->addAudit($attributes);

        return $this->productProvider->provide($type)->build($id, $sku, $templateId, $categories, $attributes);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    private function addAudit(array $attributes): array
    {
        $user = $this->security->getUser();
        if ($user) {
            $value = new StringValue(sprintf('%s %s', $user->getFirstName(), $user->getLastName()));
            $attributes[CreatedBySystemAttribute::CODE] = $value;
        }

        $attributes[CreatedAtSystemAttribute::CODE] = new StringValue((new \DateTime())->format(\DateTime::W3C));

        return $attributes;
    }
}
