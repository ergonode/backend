<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Domain\Command;

use Ergonode\Importer\Domain\Command\CreateSourceCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Webmozart\Assert\Assert;

class CreateMagento1CsvSourceCommand implements CreateSourceCommandInterface
{
    private SourceId $id;

    private string $name;

    private Language $defaultLanguage;

    private ?string $host;

    /**
     * @var Language[]
     */
    private array $languages;

    /**
     * @var AttributeId[]
     */
    private array $attributes;

    /**
     * @var bool[]
     */
    private array $import;

    /**
     * @param Language[]    $languages
     * @param AttributeId[] $attributes
     * @param array         $import
     */
    public function __construct(
        SourceId $id,
        string $name,
        Language $defaultLanguage,
        ?string $host = null,
        array $languages = [],
        array $attributes = [],
        array $import = []
    ) {
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::allIsInstanceOf($attributes, AttributeId::class);

        $this->id = $id;
        $this->name = $name;
        $this->defaultLanguage = $defaultLanguage;
        $this->host = $host;
        $this->languages = $languages;
        $this->attributes = $attributes;
        $this->import = $import;
    }

    public function getId(): SourceId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @return AttributeId[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getImport(): array
    {
        return $this->import;
    }
}
