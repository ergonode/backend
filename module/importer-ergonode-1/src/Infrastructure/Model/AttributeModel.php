<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Model;

class AttributeModel extends AbstractModel
{
    private string $code;
    private string $type;
    private string $scope;
    private array $name = [];
    private array $hint = [];
    private array $placeholder = [];

    public function __construct(string $code, string $type, string $scope)
    {
        $this->code = $code;
        $this->type = $type;
        $this->scope = $scope;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function getHint(): array
    {
        return $this->hint;
    }

    public function addHint(string $language, string $value): void
    {
        $this->hint[$language] = $value;
    }

    public function getPlaceholder(): array
    {
        return $this->placeholder;
    }

    public function addPlaceholder(string $language, string $value): void
    {
        $this->placeholder[$language] = $value;
    }

    public function getName(): array
    {
        return $this->name;
    }

    public function addName(string $language, string $value): void
    {
        $this->name[$language] = $value;
    }
}
