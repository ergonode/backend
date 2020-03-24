<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Application\Provider;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Symfony\Component\Form\FormInterface;

/**
 */
interface ExportProfileFormFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param AbstractExportProfile|null $exportProfile
     *
     * @return FormInterface
     */
    public function create(AbstractExportProfile $exportProfile = null): FormInterface;
}
