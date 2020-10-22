<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Configuration\Column;

class AttributeColumn implements ConfigurationColumnInterface
{
    public const TYPE = 'ATTRIBUTE';

    /**
     * @var string
     */
    private string $field;

    /**
     * @var string
     */
    private string $attributeCode;

    /**
     * @var string
     */
    private string $type = self::TYPE;

    /**
     * @param string $field
     * @param string $attributeCode
     */
    public function __construct(string $field, string $attributeCode)
    {
        $this->field = $field;
        $this->attributeCode = $attributeCode;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getAttributeCode(): string
    {
        return $this->attributeCode;
    }

    /**
     * @return bool
     */
    public function isImported(): bool
    {
        return true;
    }
}
