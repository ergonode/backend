<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Domain\Entity;

use JMS\Serializer\Annotation as JMS;
use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;
use Symfony\Component\Intl\Languages;

class FileExportChannel extends AbstractChannel
{
    public const TYPE = 'file';

    public const EXPORT_FULL = 'full';
    public const EXPORT_INCREMENTAL = 'incremental';

    public const EXPORT_TYPES = [
        self::EXPORT_FULL,
        self::EXPORT_INCREMENTAL,
    ];

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $format;

    /**
     * @var Languages[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\Language>")
     */
    protected array $languages;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $exportType;

    /**
     * @param ChannelId  $id
     * @param string     $name
     * @param string     $format
     * @param string     $exportType
     * @param Language[] $languages
     *
     * @throws \Exception
     */
    public function __construct(ChannelId $id, string $name, string $format, string $exportType, array $languages = [])
    {
        parent::__construct($id, $name);
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::oneOf($exportType, self::EXPORT_TYPES);

        $this->languages = $languages;
        $this->format = $format;
        $this->exportType = $exportType;
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
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return Languages[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    /**
     * @param array $languages
     */
    public function setLanguages(array $languages): void
    {
        Assert::allIsInstanceOf($languages, Language::class);
        $this->languages = $languages;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    /**
     * @param string $exportType
     */
    public function setExportType(string $exportType): void
    {
        Assert::oneOf($exportType, self::EXPORT_TYPES);
        $this->exportType = $exportType;
    }

    /**
     * @return string
     */
    public function getExportType(): string
    {
        return $this->exportType;
    }
}
