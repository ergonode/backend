<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command\Import\Attribute;

use Ergonode\Importer\Domain\Command\ImporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

abstract class AbstractImportAttributeCommand implements ImporterCommandInterface
{
    private ImportLineId $id;
    private ImportId $importId;
    private string $code;
    private string $type;
    private array $label;
    private array $hint;
    private array $placeholder;
    private string $scope;
    private array $parameters;

    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $code,
        string $type,
        array $label,
        array $hint,
        array $placeholder,
        string $scope,
        array $parameters = []
    ) {
        $this->id = $id;
        $this->importId = $importId;
        $this->code = $code;
        $this->type = $type;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
        $this->scope = $scope;
        $this->parameters = $parameters;
    }

    public function getId(): ImportLineId
    {
        return $this->id;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
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

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getParameter(string $name): ?string
    {
        return $this->parameters[$name] ?? null;
    }

    public function getLabel(): array
    {
        return $this->label;
    }

    public function getHint(): array
    {
        return $this->hint;
    }

    public function getPlaceholder(): array
    {
        return $this->placeholder;
    }
}
