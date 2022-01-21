<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Application\Model;

use Ergonode\ExporterFile\Application\Validator\SegmentNotChanged;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

/**
 * @SegmentNotChanged(groups={"Update"})
 */
class ExporterFileConfigurationModel
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2)
     */
    public ?string $name = null;

    /**
     * @var array
     *
     * @Assert\Count(min=1, minMessage="At least one language must be selected")
     */
    public array $languages = [];

    /**
     * @Assert\NotBlank()
     */
    public ?string $exportType = null;

    /**
     * @Assert\NotBlank()
     */
    public ?string $format = null;

    public ?string $segmentId = null;

    public ?FileExportChannel $channel = null;

    public function __construct(FileExportChannel $channel = null)
    {
        if ($channel) {
            $this->channel = $channel;
            $this->name = $channel->getName();
            $this->format = $channel->getFormat();
            $this->exportType = $channel->getExportType();
            $this->segmentId = $channel->getSegmentId() ? $channel->getSegmentId()->getValue() : null;
            $this->languages = $channel->getLanguages();
        }
    }
}
