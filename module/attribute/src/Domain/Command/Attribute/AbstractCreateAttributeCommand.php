<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

abstract class AbstractCreateAttributeCommand implements CreateAttributeCommandInterface
{
    private AttributeId $attributeId;

    private AttributeCode $code;

    private AttributeScope $scope;

    /**
     * @var array
     */
    private array $groups;

    private TranslatableString $label;

    private TranslatableString $hint;

    private TranslatableString $placeholder;

    /**
     * @param AttributeGroupId[] $groups
     */
    public function __construct(
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        array $groups = []
    ) {
        Assert::allIsInstanceOf($groups, AttributeGroupId::class);
        $this->attributeId = AttributeId::fromKey($code->getValue());
        $this->code = $code;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
        $this->scope = $scope;
        $this->groups = $groups;
    }

    public function getId(): AttributeId
    {
        return $this->attributeId;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getScope(): AttributeScope
    {
        return $this->scope;
    }

    /**
     * @return AttributeGroupId[]
     */
    public function getGroups(): array
    {
        return $this->groups;
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
}
