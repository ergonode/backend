<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;

/**
 */
class ExporterFileConfigurationModel
{
    /**
     * @var string|null
     *
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
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public ?string $exportType = null;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public ?string $format = null;

    /**
     * @param FileExportChannel|null $channel
     */
    public function __construct(FileExportChannel $channel = null)
    {
        if ($channel) {
            $this->name = $channel->getName();
            $this->format = $channel->getFormat();
            $this->exportType = $channel->getExportType();
            $this->languages = $channel->getLanguages();
        }
    }
}
