<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

class AttributeCreatedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $id;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $code;

    /**
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @var TranslatableString;
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $label;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $hint;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $placeholder;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeScope")
     */
    private AttributeScope $scope;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private array $parameters;

    /**
     * @JMS\Type("bool")
     */
    private bool $system;

    /**
     * @param array $parameters
     */
    public function __construct(
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        string $type,
        array $parameters = [],
        bool $system = false
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->label = $label;
        $this->hint = $hint;
        $this->scope = $scope;
        $this->placeholder = $placeholder;
        $this->parameters = $parameters;
        $this->system = $system;
    }

    public function getAggregateId(): AttributeId
    {
        return $this->id;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    public function getPlaceholder(): TranslatableString
    {
        return $this->placeholder;
    }

    public function getScope(): AttributeScope
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function isSystem(): bool
    {
        return $this->system;
    }
}
