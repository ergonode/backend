<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Event\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class AttributeCreatedEvent implements AggregateEventInterface
{
    private AttributeId $id;

    private AttributeCode $code;

    private string $type;

    private TranslatableString $label;

    private TranslatableString $hint;

    private TranslatableString $placeholder;

    private AttributeScope $scope;

    /**
     * @var array
     */
    private array $parameters;

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
