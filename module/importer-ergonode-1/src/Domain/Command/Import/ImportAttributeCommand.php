<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Domain\Command\Import;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

class ImportAttributeCommand implements DomainCommandInterface
{
    private ImportId $importId;
    private AttributeId $id;
    private AttributeCode $code;
    private string $type;
    private AttributeScope $scope;
    private array $parameters;
    private array $label;
    private array $hint;
    private array $placeholder;

    public function __construct(
        ImportId $importId,
        AttributeId $id,
        AttributeCode $code,
        string $type,
        AttributeScope $scope,
        array $parameters,
        array $label,
        array $hint,
        array $placeholder
    ) {
        $this->importId = $importId;
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->scope = $scope;
        $this->parameters = $parameters;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
    }

    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    public function getId(): AttributeId
    {
        return $this->id;
    }

    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getScope(): AttributeScope
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
