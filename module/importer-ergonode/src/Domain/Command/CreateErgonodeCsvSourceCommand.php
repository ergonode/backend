<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Domain\Command;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class CreateErgonodeCsvSourceCommand implements DomainCommandInterface
{
    /**
     * @var SourceId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SourceId")
     */
    private SourceId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @var string|null
     *
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
     * @param SourceId      $id
     * @param string        $name
     * @param Language      $defaultLanguage
     * @param string|null   $host
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

    /**
     * @return SourceId
     */
    public function getId(): SourceId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    /**
     * @return string|null
     */
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
