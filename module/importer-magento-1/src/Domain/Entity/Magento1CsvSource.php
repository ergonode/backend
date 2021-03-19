<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterMagento1\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class Magento1CsvSource extends AbstractSource
{
    public const TYPE = 'magento-1-csv';

    public const DELIMITER = 'delimiter';
    public const ENCLOSURE = 'enclosure';
    public const ESCAPE = 'escape';

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

    public const DEFAULT = [
        self::DELIMITER => ',',
        self::ENCLOSURE => '"',
        self::ESCAPE => '\\',
    ];

    /**
     * @var Language[]
     */
    private array $languages;

    /**
     * @var AttributeId[]
     */
    private array $attributes;

    private ?string $host;

    private Language $defaultLanguage;

    /**
     * @var string[]
     */
    private array $import;

    /**
     * @param array $languages
     * @param array $attributes
     * @param array $imports
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

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getDelimiter(): string
    {
        return self::DEFAULT[self::DELIMITER];
    }

    public function getEnclosure(): string
    {
        return self::DEFAULT[self::ENCLOSURE];
    }

    public function getEscape(): string
    {
        return self::DEFAULT[self::ESCAPE];
    }

    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

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

    public function setHost(?string $host): void
    {
        $this->host = $host;
    }

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

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function import(string $step): bool
    {
        if (array_key_exists($step, array_flip($this->import))) {
            return true;
        }

        return false;
    }
}
