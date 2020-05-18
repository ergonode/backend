<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Domain\Entity;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileInterface;

/**
 */
class FileExportProfile extends AbstractExportProfile implements ExportProfileInterface
{
    public const TYPE = 'file';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
