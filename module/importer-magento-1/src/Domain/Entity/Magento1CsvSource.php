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
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $host;

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
     * @param string   $host
     * @param array    $languages
     * @param array    $imports
     */
    public function __construct(
        SourceId $id,
        string $name,
        Language $defaultLanguage,
        string $host,
        array $languages = [],
        array $imports = []
    ) {
        parent::__construct($id, $name);
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::allBoolean($imports);
        Assert::allString(array_keys($languages));
        Assert::allString(array_keys($imports));
        Assert::notEmpty($host);

        $this->languages = $languages;
        $this->host = $host;
        $this->defaultLanguage = $defaultLanguage;
        $this->import = [
            self::ATTRIBUTES => false,
            self::TEMPLATES => false,
            self::CATEGORIES => false,
            self::MULTIMEDIA => false,
            self::PRODUCTS => false,
        ];

        foreach ($imports as $key => $import) {
            if (array_key_exists($key, $this->import)) {
                $this->import[$key] = $import;
            }
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
     * @return string
     */
    public function getHost(): string
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
