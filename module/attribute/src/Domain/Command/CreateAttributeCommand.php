<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateAttributeCommand implements DomainCommandInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    /**
     * @var AttributeType
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeType")
     */
    private AttributeType $type;

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
     * @var string[]
     *
     * @JMS\Type("array")
     */
    private array $parameters;

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
     * @var OptionInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Attribute\Domain\ValueObject\OptionInterface>")
     */
    private array $options;

    /**
     * @param AttributeType      $type
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param array              $groups
     * @param array              $parameters
     * @param OptionInterface[]  $options
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeType $type,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        array $groups = [],
        array $parameters = [],
        array $options = []
    ) {
        $this->attributeId = AttributeId::fromKey($code->getValue());
        $this->code = $code;
        $this->type = $type;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
        $this->multilingual = $multilingual;
        $this->groups = $groups;
        $this->parameters = $parameters;
        $this->options = $options;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->attributeId;
    }

    /**
     * @return AttributeType
     */
    public function getType(): AttributeType
    {
        return $this->type;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasParameter(string $key): bool
    {
        return isset($this->parameters[$key]);
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return $this->multilingual;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getParameter(string $key)
    {
        return $this->parameters[$key];
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return OptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->options;
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
