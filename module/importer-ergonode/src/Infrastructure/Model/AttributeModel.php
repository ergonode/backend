<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Model;

/**
 */
final class AttributeModel
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $code;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $scope;

    /**
     * @var array
     */
    private array $name;

    /**
     * @var array
     */
    private array $hint;

    /**
     * @var array
     */
    private array $placeholder;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * @param string $id
     * @param string $code
     * @param string $type
     * @param string $scope
     */
    public function __construct(string $id, string $code, string $type, string $scope)
    {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->scope = $scope;
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
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getScope(): string
    {
        return $this->scope;
    }

    /**
     * @return array
     */
    public function getHint(): array
    {
        return $this->hint;
    }

    /**
     * @param string $language
     * @param string $value
     */
    public function addHint(string $language, string $value): void
    {
        $this->hint[$language] = $value;
    }

    /**
     * @return array
     */
    public function getPlaceholder(): array
    {
        return $this->placeholder;
    }

    /**
     * @param string $language
     * @param string $value
     */
    public function addPlaceholder(string $language, string $value): void
    {
        $this->placeholder[$language] = $value;
    }

    /**
     * @return array
     */
    public function getName(): array
    {
        return $this->name;
    }

    /**
     * @param string $language
     * @param string $value
     */
    public function addName(string $language, string $value): void
    {
        $this->name[$language] = $value;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addParameter(string $name, string $value): void
    {
        $this->parameters[$name] = $value;
    }
}
