<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
final class ImportAttributeCommand implements DomainCommandInterface
{
    /**
     * @var string
     */
    private string $type;

    /**
     * @var AttributeId
     */
    private AttributeId $id;

    /**
     * @var AttributeCode
     */
    private AttributeCode $code;

    /**
     * @var TranslatableString
     */
    private TranslatableString $hint;

    /**
     * @var TranslatableString
     */
    private TranslatableString $placeholder;

    /**
     * @var TranslatableString
     */
    private TranslatableString $label;

    /**
     * @param string             $type
     * @param AttributeId        $id
     * @param AttributeCode      $code
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param TranslatableString $label
     */
    public function __construct(
        string $type,
        AttributeId $id,
        AttributeCode $code,
        TranslatableString $hint,
        TranslatableString $placeholder,
        TranslatableString $label
    ) {
        $this->type = $type;
        $this->id = $id;
        $this->code = $code;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
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
     * @return TranslatableString
     */
    public function getLabel(): TranslatableString
    {
        return $this->label;
    }
}
