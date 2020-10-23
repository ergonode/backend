<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Configuration\Column;

class ProposalColumn implements ConfigurationColumnInterface
{
    public const TYPE = 'PROPOSAL';

    private string $field;

    private bool $imported;

    private string $attributeType;

    private string $attributeCode;

    private string $type = self::TYPE;

    public function __construct(string $field, string $attributeCode, string $attributeType)
    {
        $this->field = $field;
        $this->imported = true;
        $this->attributeCode = $attributeCode;
        $this->attributeType = $attributeType;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function isImported(): bool
    {
        return $this->imported;
    }

    public function getAttributeCode(): string
    {
        return $this->attributeCode;
    }

    public function getAttributeType(): string
    {
        return $this->attributeType;
    }
}
