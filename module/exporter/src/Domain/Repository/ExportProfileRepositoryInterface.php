<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Repository;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Domain\Entity\Profile\ExportProfileId;

/**
 */
interface ExportProfileRepositoryInterface
{
    /**
     * @param ExportProfileId $id
     *
     * @return AbstractExportProfile|null
     *
     * @throws \ReflectionException
     */
    public function load(ExportProfileId $id): ?AbstractExportProfile;

    /**
     * @param AbstractExportProfile $exportProfile
     */
    public function save(AbstractExportProfile $exportProfile): void;

    /**
     * @param ExportProfileId $id
     *
     * @return bool
     */
    public function exists(ExportProfileId $id): bool;
}
