<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Domain\Command;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Entity\ProductDraftId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class ChangeProductAttributeValueCommand implements DomainCommandInterface
{
    /**
     * @var ProductDraftId
     *
     * @JMS\Type("Ergonode\Editor\Domain\Entity\ProductDraftId")
     */
    private $id;

    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     */
    private $attributeId;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private $language;

    /**
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private $value;

    /**
     * @param ProductDraftId $id
     * @param AttributeId    $attributeId
     * @param Language       $language
     * @param mixed          $value
     */
    public function __construct(ProductDraftId $id, AttributeId $attributeId, Language $language, $value = null)
    {
        $this->id = $id;
        $this->attributeId = $attributeId;
        $this->language = $language;
        $this->value = $value;
    }

    /**
     * @return ProductDraftId
     */
    public function getId(): ProductDraftId
    {
        return $this->id;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return Language
     */
    public function getLanguage(): Language
    {
        return $this->language;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
