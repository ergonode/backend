<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento1\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

/**
 */
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
     * @JMS\Type("array<string, bool>")
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

        foreach (self::STEPS as $step) {
            $this->import[$step] = false;
        }

        foreach ($imports as $import) {
            $this->import[$import] = true;
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
        return self::DEFAULT[self::DELIMITER];
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return self::DEFAULT[self::ENCLOSURE];
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return self::DEFAULT[self::ESCAPE];
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
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
        if (array_key_exists($step, $this->import)) {
            return $this->import[$step];
        }

        return false;
    }
}
