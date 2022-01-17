<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Entity;

use Ergonode\Channel\Domain\Entity\AbstractChannel;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Webmozart\Assert\Assert;

class FileExportChannel extends AbstractChannel
{
    public const TYPE = 'file';

    public const EXPORT_FULL = 'full';
    public const EXPORT_INCREMENTAL = 'incremental';

    public const EXPORT_TYPES = [
        self::EXPORT_FULL,
        self::EXPORT_INCREMENTAL,
    ];

    protected string $format;

    /**
     * @var Language[]
     */
    protected array $languages;

    protected string $exportType;

    private ?SegmentId $segmentId;

    /**
     * @param Language[] $languages
     *
     * @throws \Exception
     */
    public function __construct(
        ChannelId $id,
        string $name,
        string $format,
        string $exportType,
        ?SegmentId $segmentId,
        array $languages = []
    ) {
        parent::__construct($id, $name);
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::oneOf($exportType, self::EXPORT_TYPES);

        $this->languages = $languages;
        $this->format = $format;
        $this->exportType = $exportType;
        $this->segmentId = $segmentId;
    }

    public static function getType(): string
    {
        return self::TYPE;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return Language[]
     */
    public function getLanguages(): array
    {
        return $this->languages;
    }

    public function getSegmentId(): ?SegmentId
    {
        return $this->segmentId;
    }

    public function setSegmentId(?SegmentId $segmentId): void
    {
        $this->segmentId = $segmentId;
    }

    /**
     * @param array $languages
     */
    public function setLanguages(array $languages): void
    {
        Assert::allIsInstanceOf($languages, Language::class);
        $this->languages = $languages;
    }

    public function setFormat(string $format): void
    {
        $this->format = $format;
    }

    public function setExportType(string $exportType): void
    {
        Assert::oneOf($exportType, self::EXPORT_TYPES);
        $this->exportType = $exportType;
    }

    public function getExportType(): string
    {
        return $this->exportType;
    }
}
