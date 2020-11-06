<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;

abstract class AbstractCreateAttributeCommand implements CreateAttributeCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private AttributeCode $code;

    /**
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeScope")
     */
    private AttributeScope $scope;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private array $groups;

    /**
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
