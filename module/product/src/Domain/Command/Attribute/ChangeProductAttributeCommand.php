<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Domain\Command\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Command\ProductCommandInterface;

class ChangeProductAttributeCommand implements ProductCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ProductId")
     */
    private ProductId $id;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $language;

    /**
     * @var string|array|null
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param mixed $value
     */
    public function __construct(ProductId $id, AttributeId $attributeId, Language $language, $value = null)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
        $this->language = $language;
        $this->value = $value;
    }

    public function getId(): ProductId
    {
        return $this->id;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return string|array|null
     */
    public function getValue()
    {
        return $this->value;
    }
}
