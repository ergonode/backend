<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Domain\Command\Export;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class Shopware6ExportTreeCommand implements DomainCommandInterface
{
    /**
     * @var ExportId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @param ExportId $exportId
     */
    public function __construct(ExportId $exportId)
    {
        $this->exportId = $exportId;
    }

    /**
     * @return ExportId
     */
    public function getExportId(): ExportId
    {
        return $this->exportId;
    }
}
