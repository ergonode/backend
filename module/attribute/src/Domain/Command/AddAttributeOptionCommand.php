<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command;

use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class AddAttributeOptionCommand implements DomainCommandInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\Attribute\Domain\Entity\AttributeId")
     */
    private $attributeId;

    /**
     * @var OptionKey
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private $optionKey;

    /**
     * @var OptionInterface
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionInterface")
     */
    private $option;

    /**
     * @param AttributeId     $attributeId
     * @param OptionKey       $optionKey
     * @param OptionInterface $option
     */
    public function __construct(AttributeId $attributeId, OptionKey $optionKey, OptionInterface $option)
    {
        $this->attributeId = $attributeId;
        $this->optionKey = $optionKey;
        $this->option = $option;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return OptionKey
     */
    public function getOptionKey(): OptionKey
    {
        return $this->optionKey;
    }

    /**
     * @return OptionInterface
     */
    public function getOption(): OptionInterface
    {
        return $this->option;
    }
}
