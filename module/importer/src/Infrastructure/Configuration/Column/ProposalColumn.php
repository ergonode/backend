<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Configuration\Column;

/**
 */
class ProposalColumn implements ConfigurationColumnInterface
{
    public const TYPE = 'PROPOSAL';

    /**
     * @var string
     */
    private string $field;

    /**
     * @var bool
     */
    private bool $imported;

    /**
     * @var string
     */
    private string $attributeType;

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
     * @param string $attributeType
     */
    public function __construct(string $field, string $attributeCode, string $attributeType)
    {
        $this->field = $field;
        $this->imported = true;
        $this->attributeCode = $attributeCode;
        $this->attributeType = $attributeType;
    }

    /**
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * @return bool
     */
    public function isImported(): bool
    {
        return $this->imported;
    }

    /**
     * @return string
     */
    public function getAttributeCode(): string
    {
        return $this->attributeCode;
    }

    /**
     * @return string
     */
    public function getAttributeType(): string
    {
        return $this->attributeType;
    }
}
