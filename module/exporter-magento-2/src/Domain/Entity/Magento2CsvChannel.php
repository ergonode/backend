<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterMagento2\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class Magento2CsvChannel extends AbstractChannel
{
    public const TYPE = 'magento-2-csv';

    private string $filename;

    private Language $defaultLanguage;

    public function __construct(ChannelId $id, string $name, string $filename, Language $defaultLanguage)
    {
        parent::__construct($id, $name);
        $this->filename = $filename;
        $this->defaultLanguage = $defaultLanguage;
    }

    public static function getType(): string
    {
        return self::TYPE;
    }

    public function getFilename(): string
    {
        return $this->filename;
    }

    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    public function setDefaultLanguage(Language $defaultLanguage): void
    {
        $this->defaultLanguage = $defaultLanguage;
    }
}
