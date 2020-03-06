<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeCreatedEvent implements DomainEventInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $code;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $class;

    /**
     * @var TranslatableString;
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
     * @var bool
     *
     * @JMS\Type("boolean")
     */
    private bool $multilingual;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private array $parameters;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $system;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $editable;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $deletable;

    /**
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param bool               $multilingual
     * @param string             $type
     * @param string             $class
     * @param array              $parameters
     * @param bool               $editable
     * @param bool               $deletable
     * @param bool               $system
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        bool $multilingual,
        string $type,
        string $class,
        array $parameters = [],
        bool $editable = true,
        bool $deletable = true,
        bool $system = false
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->class = $class;
        $this->label = $label;
        $this->hint = $hint;
        $this->multilingual = $multilingual;
        $this->placeholder = $placeholder;
        $this->parameters = $parameters;
        $this->system = $system;
        $this->editable = $editable;
        $this->deletable = $deletable;
    }

    /**
     * @return AttributeId
     */
    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
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
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @return TranslatableString
     */
    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    /**
     * @return TranslatableString
     */
    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    /**
     * @return TranslatableString
     */
    public function getPlaceholder(): TranslatableString
    {
        return $this->placeholder;
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
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->system;
    }

    /**
     * @return bool
     */
    public function isEditable(): bool
    {
        return $this->editable;
    }

    /**
     * @return bool
     */
    public function isDeletable(): bool
    {
        return $this->deletable;
    }
}
