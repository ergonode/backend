<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Domain\Command;

use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\ValueObject\Sku;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateProductCommand
{
    /**
     * @var ProductId
     *
     * @JMS\Type("Ergonode\Product\Domain\Entity\ProductId")
     */
    private $id;

    /**
     * @var Sku
     *
     * @JMS\Type("Ergonode\Product\Domain\ValueObject\Sku")
     */
    private $sku;

    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\DesignerTemplateId")
     */
    private $templateId;

    /**
     * @var CategoryId[]
     *
     * @JMS\Type("array<string, Ergonode\Category\Domain\Entity\CategoryId>")
     */
    private $categories;

    /**
     * @var ValueInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Value\Domain\ValueObject\ValueInterface>")
     */
    private $attributes;

    /**
     * @param Sku        $sku
     * @param TemplateId $templateId
     * @param array      $categories
     *
     * @param array      $attributes
     *
     * @throws \Exception
     */
    public function __construct(Sku $sku, TemplateId $templateId, array $categories = [], array $attributes = [])
    {
        $this->id = ProductId::generate();
        $this->sku = $sku;
        $this->templateId = $templateId;
        $this->categories = $categories;
        $this->attributes = $attributes;
    }

    /**
     * @return ProductId
     */
    public function getId(): ProductId
    {
        return $this->id;
    }

    /**
     * @return Sku
     */
    public function getSku(): Sku
    {
        return $this->sku;
    }

    /**
     * @return TemplateId
     */
    public function getTemplateId(): TemplateId
    {
        return $this->templateId;
    }

    /**
     * @return array
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * @return ValueInterface[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
