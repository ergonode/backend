<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

final class ImportAttributeCommand implements DomainCommandInterface
{
    private string $type;
    private AttributeId $id;
    private AttributeCode $code;
    private TranslatableString $hint;
    private TranslatableString $placeholder;
    private TranslatableString $label;

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

    public function getType(): string
    {
        return $this->type;
    }

    public function getId(): AttributeId
    {
        return $this->id;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    public function getPlaceholder(): TranslatableString
    {
        return $this->placeholder;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }
}
