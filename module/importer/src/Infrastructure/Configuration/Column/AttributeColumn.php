<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Configuration\Column;

class AttributeColumn implements ConfigurationColumnInterface
{
    public const TYPE = 'ATTRIBUTE';

    private string $field;

    private string $attributeCode;

    private string $type = self::TYPE;

    public function __construct(string $field, string $attributeCode)
    {
        $this->field = $field;
        $this->attributeCode = $attributeCode;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getAttributeCode(): string
    {
        return $this->attributeCode;
    }

    public function isImported(): bool
    {
        return true;
    }
}
