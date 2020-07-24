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

/**
 */
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
     * @param ChannelId $id
     * @param string    $name
     * @param string    $format
     */
    public function __construct(ChannelId $id, string $name, string $format)
    {
        $this->id = $id;
        $this->name = $name;
        $this->format = $format;
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
}
