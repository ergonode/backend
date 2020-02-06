<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
interface ExportProfileFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param ExportProfileId $exportProfileId
     * @param string          $name
     * @param array           $params
     *
     * @return AbstractExportProfile
     */
    public function create(ExportProfileId $exportProfileId, string $name, array $params = []): AbstractExportProfile;
}
