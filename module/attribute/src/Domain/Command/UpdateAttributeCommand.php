<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command;

use Ergonode\Attribute\Application\Form\Model\AttributeOptionModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption;
use Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class UpdateAttributeCommand implements DomainCommandInterface
{
    /**
     * @var AttributeId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private $attributeId;

    /**
     * @var string
     */
    private $type;

    /**
     * @var AttributeCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeCode")
     */
    private $code;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private $parameters;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private $groups;

    /**
     * @var OptionInterface[]
     *
     * @JMS\Type("array<string, Ergonode\Attribute\Domain\ValueObject\OptionInterface>")
     */
    private $options;

    /**
     * @var TranslatableString|null
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $label;

    /**
     * @var TranslatableString|null
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $hint;

    /**
     * @var TranslatableString|null
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $placeholder;

    /**
     * @param AttributeId        $id
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param array              $groups
     * @param array              $parameters
     * @param OptionInterface[]  $options
     */
    public function __construct(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        array $groups = [],
        array $parameters = [],
        array $options = []
    ) {
        Assert::allString(array_keys($options));
        Assert::allIsInstanceOf($options, OptionInterface::class);
        $this->attributeId = $id;
        $this->parameters = $parameters;
        $this->groups = $groups;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
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
     * @return OptionInterface[]
     */
    public function getOptions(): array
    {
        return $this->options;
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
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
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
}
