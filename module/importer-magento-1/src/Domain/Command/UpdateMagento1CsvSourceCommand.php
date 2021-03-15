<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Domain\Command;

use Ergonode\Importer\Domain\Command\UpdateSourceCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;

class UpdateMagento1CsvSourceCommand implements UpdateSourceCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SourceId")
     */
    private SourceId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @JMS\Type("string")
     */
    private ?string $host;

    /**
     * @var Language[]
     *
     * @JMS\Type("array<string, Ergonode\Core\Domain\ValueObject\Language>")
     */
    private array $languages;

    /**
     * @var AttributeId[]
     *
     * @JMS\Type("array<string, Ergonode\SharedKernel\Domain\Aggregate\AttributeId>")
     */
    private array $attributes;

    /**
     * @var array
     *
     * @JMS\Type("array<string, bool>")
     */
    private array $import;

    /**
     * @param array|Language[]    $languages
     * @param array|AttributeId[] $attributes
     * @param array               $import
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
