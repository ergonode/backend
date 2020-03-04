<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Entity;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class Magento2ExportCsvProfile extends AbstractExportProfile
{
    public const TYPE = 'magento-2-csv';

    /**
     * Magento2ExportCsvProfile constructor.
     * @param ExportProfileId $id
     * @param string          $name
     * @param string          $filename
     */
    public function __construct(ExportProfileId $id, string $name, string $filename)
    {
        parent::__construct($id, $name);
        $this->configuration['file'] = $filename;
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
    public function getFile(): string
    {
        return $this->configuration['file'];
    }
}
