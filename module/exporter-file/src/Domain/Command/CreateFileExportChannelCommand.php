<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command;

use Ergonode\Channel\Domain\Command\CreateChannelCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class CreateFileExportChannelCommand implements CreateChannelCommandInterface
{
    protected ChannelId $id;

    protected string $name;

    protected string $format;

    /**
     * @var Language[]
     */
    protected array $languages;

    protected string $exportType;

    protected ?SegmentId $segmentId;

    /**
     * @param Language[] $languages
     */
    public function __construct(
        ChannelId $id,
        string $name,
        string $format,
        string $exportType,
        ?SegmentId $segmentId = null,
        array $languages = []
    ) {
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::oneOf($exportType, FileExportChannel::EXPORT_TYPES);

        $this->id = $id;
        $this->name = $name;
        $this->format = $format;
        $this->exportType = $exportType;
        $this->segmentId = $segmentId;
        $this->languages = $languages;
    }


    public function getId(): ChannelId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    public function getExportType(): string
    {
        return $this->exportType;
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
}
