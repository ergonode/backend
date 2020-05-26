<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Processor;

use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;

/**
 */
interface ExportProcessorInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param AbstractExportProfile $profile
     */
    public function start(AbstractExportProfile $profile): void;

    /**
     * @param AbstractExportProfile $profile
     * @param AbstractProduct       $product
     *
     * @return mixed
     */
    public function process(AbstractExportProfile $profile, AbstractProduct $product): void;

    /**
     * @param AbstractExportProfile $profile
     */
    public function end(AbstractExportProfile $profile): void;
}
