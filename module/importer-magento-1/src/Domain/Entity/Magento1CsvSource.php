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

/**
 */
class Magento1CsvSource extends AbstractSource
{
    public const TYPE = 'magento-1-csv';

    public const DELIMITER = 'delimiter';
    public const ENCLOSURE = 'enclosure';
    public const ESCAPE = 'escape';

    public const DEFAULT = [
        self::DELIMITER => ',',
        self::ENCLOSURE => '"',
        self::ESCAPE => '\\',
    ];

    /**
     * @param SourceId $id
     * @param string   $filename
     */
    public function __construct(SourceId $id, string $filename)
    {
        parent::__construct($id);

        $this->configuration['file'] = $filename;
        $this->configuration = array_merge(self::DEFAULT, $this->configuration);
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
    public function getFile(): string
    {
        return $this->configuration['file'];
    }

    /**
     * @return string
     */
    public function getDelimiter(): string
    {
        return $this->configuration[self::DELIMITER];
    }

    /**
     * @return string
     */
    public function getEnclosure(): string
    {
        return $this->configuration[self::ENCLOSURE];
    }

    /**
     * @return string
     */
    public function getEscape(): string
    {
        return $this->configuration[self::ESCAPE];
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return new Language(Language::PL);
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return [
            'poland_pl' => new Language(Language::PL),
            'france_fr' => new Language(Language::FR),
            'germany_de' => new Language(Language::DE),
            'romania_ro' => new Language(Language::RO),
            'turkey_tr' => new Language(Language::TR),
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return 'https://husse-eu.global.ssl.fastly.net/media/catalog/product/cache/8/image/9df78eab33525d08d6e5fb8d27136e95';
    }

    /**
     * @return bool
     */
    public function importMultimedia(): bool
    {
        return false;
    }
}
