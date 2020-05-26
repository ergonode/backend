<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;

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

    public ?string $format = null;

    /**
     * @param FileExportProfile|null $exportProfile
     */
    public function __construct(FileExportProfile $exportProfile = null)
    {
        if ($exportProfile) {
            $this->name = $exportProfile->getName();
            $this->format = $exportProfile->getFormat();
        }
    }
}
