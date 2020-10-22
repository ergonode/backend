<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Entity;

use Ergonode\Core\Domain\ValueObject\Language;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;

class Magento2CsvChannel extends AbstractChannel
{
    public const TYPE = 'magento-2-csv';

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $filename;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $defaultLanguage;

    /**
     * @param ChannelId $id
     * @param string    $name
     * @param string    $filename
     * @param Language  $defaultLanguage
     */
    public function __construct(ChannelId $id, string $name, string $filename, Language $defaultLanguage)
    {
        parent::__construct($id, $name);
        $this->filename = $filename;
        $this->defaultLanguage = $defaultLanguage;
    }

    /**
     * @return string
     */
    public static function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @return Language
     */
    public function getDefaultLanguage(): Language
    {
        return $this->defaultLanguage;
    }

    /**
     * @param string $filename
     */
    public function setFilename(string $filename): void
    {
        $this->filename = $filename;
    }

    /**
     * @param Language $defaultLanguage
     */
    public function setDefaultLanguage(Language $defaultLanguage): void
    {
        $this->defaultLanguage = $defaultLanguage;
    }
}
