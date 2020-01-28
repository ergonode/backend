<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ExportAttribute
{
    public const OPTIONS = 'options';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    protected TranslatableString $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $type;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    protected bool $multilingual;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    protected array $parameters;

    /**
     * @var bool
     *
     * @JMS\Type("bool")
     */
    private bool $system;

    /**
     * ExportAttribute constructor.
     * @param string             $id
     * @param string             $code
     * @param TranslatableString $name
     * @param string             $type
     * @param bool               $multilingual
     * @param array              $parameters
     * @param bool               $system
     */
    public function __construct(
        string $id,
        string $code,
        TranslatableString $name,
        string $type,
        bool $multilingual = false,
        array $parameters = [],
        bool $system = false
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->type = $type;
        $this->multilingual = $multilingual;
        $this->parameters = $parameters;
        $this->system = $system;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function isMultilingual(): bool
    {
        return $this->multilingual;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->system;
    }

    /**
     * @param TranslatableString $name
     */
    public function changeName(TranslatableString $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function changeParameter(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function changeOrCreateOption(string $key, string $value): void
    {
        $this->parameters[self::OPTIONS][$key] = $value;
    }

    /**
     * @param string $key
     */
    public function removeOption(string $key):void
    {
        unset($this->parameters[self::OPTIONS][$key]);
    }
}
