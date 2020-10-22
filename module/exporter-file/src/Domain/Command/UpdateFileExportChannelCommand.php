<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ChannelId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Core\Domain\ValueObject\Language;
use Webmozart\Assert\Assert;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

class UpdateFileExportChannelCommand implements DomainCommandInterface
{
    /**
     * @var  ChannelId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ChannelId")
     */
    protected ChannelId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $format;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $exportType;

    /**
     * @var Language[]
     *
     * @JMS\Type("array<Ergonode\Core\Domain\ValueObject\Language>")
     */
    protected array $languages;

    /**
     * @param ChannelId $id
     * @param string    $name
     * @param string    $format
     * @param string    $exportType
     * @param array     $languages
     */
    public function __construct(ChannelId $id, string $name, string $format, string $exportType, array $languages = [])
    {
        Assert::allIsInstanceOf($languages, Language::class);
        Assert::oneOf($exportType, FileExportChannel::EXPORT_TYPES);

        $this->id = $id;
        $this->name = $name;
        $this->format = $format;
        $this->exportType = $exportType;
        $this->languages = $languages;
    }

    /**
     * @return ChannelId
     */
    public function getId(): ChannelId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @return string
     */
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
}
