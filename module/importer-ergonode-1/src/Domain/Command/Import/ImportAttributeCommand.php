<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Command\Import;

use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportLineId;

class ImportAttributeCommand implements DomainCommandInterface
{
    private ImportLineId $id;
    private ImportId $importId;
    private string $code;
    private string $type;
    private string $scope;
    private array $parameters;
    private array $label;
    private array $hint;
    private array $placeholder;

    public function __construct(
        ImportLineId $id,
        ImportId $importId,
        string $code,
        string $type,
        string $scope,
        array $parameters,
        array $label,
        array $hint,
        array $placeholder
    ) {
        $this->id = $id;
        $this->importId = $importId;
        $this->code = $code;
        $this->type = $type;
        $this->scope = $scope;
        $this->parameters = $parameters;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
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
        if (!array_key_exists($name, $this->parameters)) {
            return null;
        }

        return $this->parameters[$name];
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
