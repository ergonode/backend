<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Factory;

use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Product\Domain\Factory\ProductFactory;
use Ergonode\Product\Domain\Factory\ProductFactoryInterface;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Provider\WorkflowProviderInterface;

class AddStatusToProductFactoryDecorator implements ProductFactoryInterface
{
    private ProductFactory $productFactory;

    protected WorkflowProviderInterface $provider;

    protected LanguageQueryInterface $query;

    public function __construct(
        ProductFactory $productFactory,
        WorkflowProviderInterface $provider,
        LanguageQueryInterface $query
    ) {
        $this->productFactory = $productFactory;
        $this->provider = $provider;
        $this->query = $query;
    }


    public function create(
        string $type,
        ProductId $id,
        Sku $sku,
        TemplateId $templateId,
        array $categories,
        array $attributes
    ): AbstractProduct {
        $attributes = $this->addStatusAttribute($attributes);

        return $this->productFactory->create($type, $id, $sku, $templateId, $categories, $attributes);
    }

    protected function addStatusAttribute(array $attributes): array
    {

        $result = [];

        foreach ($this->query->getActive() as $language) {
            $workflow = $this->provider->provide($language);
            $status = $workflow->getDefaultStatus()->getValue();
            $result[$language->getCode()] = $status;
        }
        $attributes[StatusSystemAttribute::CODE] = new TranslatableStringValue(new TranslatableString($result));

        return $attributes;
    }
}
