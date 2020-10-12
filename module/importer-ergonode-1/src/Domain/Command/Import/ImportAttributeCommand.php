<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\ImporterErgonode\Domain\Command\Import;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;

/**
 */
final class ImportAttributeCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     */
    private ImportId $importId;

    /**
     * @var AttributeId
     */
    private AttributeId $id;

    /**
     * @var AttributeCode
     */
    private AttributeCode $code;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var AttributeScope
     */
    private AttributeScope $scope;

    /**
     * @var array
     */
    private array $parameters;

    /**
     * @var array
     */
    private array $label;

    /**
     * @var array
     */
    private array $hint;

    /**
     * @var array
     */
    private array $placeholder;

    /**
     * @param ImportId       $importId
     * @param AttributeId    $id
     * @param AttributeCode  $code
     * @param string         $type
     * @param AttributeScope $scope
     * @param array          $parameters
     * @param array          $label
     * @param array          $hint
     * @param array          $placeholder
     */
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

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
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
     * @return AttributeScope
     */
    public function getScope(): AttributeScope
    {
        return $this->scope;
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
     *
     * @return string|null
     */
    public function getParameter(string $name): ?string
    {
        if (!array_key_exists($name, $this->parameters)) {
            return null;
        }

        return $this->parameters[$name];
    }

    /**
     * @return array
     */
    public function getLabel(): array
    {
        return $this->label;
    }

    /**
     * @return array
     */
    public function getHint(): array
    {
        return $this->hint;
    }

    /**
     * @return array
     */
    public function getPlaceholder(): array
    {
        return $this->placeholder;
    }
}
