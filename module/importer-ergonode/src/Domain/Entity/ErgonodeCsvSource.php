<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterErgonode\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class ErgonodeCsvSource extends AbstractSource
{
    public const TYPE = 'ergonode-csv';

    public const DELIMITER = ',';
    public const ENCLOSURE = '"';
    public const ESCAPE = '\\';

    public const MULTIMEDIA = 'multimedia';
    public const PRODUCTS = 'products';
    public const CATEGORIES = 'categories';
    public const TEMPLATES = 'templates';
    public const ATTRIBUTES = 'attributes';

    public const STEPS = [
        self::MULTIMEDIA,
        self::PRODUCTS,
        self::CATEGORIES,
        self::TEMPLATES,
        self::ATTRIBUTES,
    ];

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
     * @var string|null
     *
     * @JMS\Type("string")
     */
    private ?string $host;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @var array
     *
     * @JMS\Type("array<string>")
     */
    private array $import;

    /**
     * @param SourceId $id
     * @param string   $name
     * @param Language $defaultLanguage
     * @param array    $languages
     * @param array    $attributes
     * @param array    $imports
     * @param string   $host
     */
    public function __construct(
        SourceId $id,
        string $name,
        Language $defaultLanguage,
        array $languages = [],
        array $attributes = [],
        array $imports = [],
        ?string $host = null
    ) {
        parent::__construct($id, $name);
        Assert::allIsInstanceOf($attributes, AttributeId::class);
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::allString($imports);
        Assert::allString(array_keys($languages));

        $this->languages = $languages;
        $this->attributes = $attributes;
        $this->host = $host;
        $this->defaultLanguage = $defaultLanguage;
        $this->import = [];

        foreach ($imports as $import) {
            $this->import[] = $import;
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return self::DELIMITER;
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return self::ENCLOSURE;
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return self::ESCAPE;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param Language[] $languages
     */
    public function setLanguages(array $languages): void
    {
        $this->languages = $languages;
    }

    /**
     * @param AttributeId[] $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @param string|null $host
     */
    public function setHost(?string $host): void
    {
        $this->host = $host;
    }

    /**
     * @param Language $defaultLanguage
     */
    public function setDefaultLanguage(Language $defaultLanguage): void
    {
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @param array $import
     */
    public function setImport(array $import): void
    {
        $this->import = $import;
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
     * @return string|null
     */
    public function getHost(): ?string
    {
        return $this->host;
    }

    /**
     * @param string $step
     *
     * @return bool
     */
    public function import(string $step): bool
    {
        if (array_key_exists($step, array_flip($this->import))) {
            return true;
        }

        return false;
    }
}
