<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Processor;

use Ergonode\Exporter\Domain\Entity\Profile\AbstractExportProfile;
use Ergonode\Exporter\Infrastructure\Exception\ExportException;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

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
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     */
    public function start(ExportId $id, AbstractExportProfile $profile): void;

    /**
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     * @param AbstractProduct       $product
     *
     * @return mixed
     *
     * @throws ExportException
     */
    public function process(ExportId $id, AbstractExportProfile $profile, AbstractProduct $product): void;

    /**
     * @param ExportId              $id
     * @param AbstractExportProfile $profile
     */
    public function end(ExportId $id, AbstractExportProfile $profile): void;
}
