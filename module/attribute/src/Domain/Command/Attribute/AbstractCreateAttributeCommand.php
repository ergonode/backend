<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
abstract class AbstractCreateAttributeCommand implements DomainCommandInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $code;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $multilingual;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private array $groups;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $label;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $hint;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $placeholder;

    /**
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param array              $groups
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        array $groups = []
    ) {
        $this->attributeId = AttributeId::fromKey($code->getValue());
        $this->code = $code;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
        $this->multilingual = $multilingual;
        $this->groups = $groups;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return $this->multilingual;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return TranslatableString|null
     */
    public function getLabel(): ?TranslatableString
    {
        return $this->label;
    }

    /**
     * @return TranslatableString|null
     */
    public function getHint(): ?TranslatableString
    {
        return $this->hint;
    }

    /**
     * @return TranslatableString|null
     */
    public function getPlaceholder(): ?TranslatableString
    {
        return $this->placeholder;
    }
}
