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
use Ergonode\Channel\Domain\Event\ChannelCreatedEvent;

/**
 */
class FileExportChannel extends AbstractChannel
{
    public const TYPE = 'file';

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
     *
     * @throws \Exception
     */
    public function __construct(ChannelId $id, string $name, string $format)
    {
        parent::__construct($id, $name);

        $this->format = $format;
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
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     */
    public function setFormat(string $format): void
    {
        $this->format = $format;
    }
}
