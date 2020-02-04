<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\ExporterMagento2\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileId;
use Ergonode\Exporter\Domain\Factory\ExportProfileFactoryInterface;
use Ergonode\ExporterMagento2\Domain\Entity\Magento2ExportCsvProfile;

/**
 */
class Magento2ExportCSVProfileFactory implements ExportProfileFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool
    {
        return $type === Magento2ExportCsvProfile::TYPE;
    }

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     * @param array           $params
     *
     * @return AbstractExportProfile
     */
    public function create(ExportProfileId $exportProfileId, string $name, array $params = []): AbstractExportProfile
    {
        return new Magento2ExportCsvProfile(
            $exportProfileId,
            $name,
            $params['filename']
        );
    }
}
