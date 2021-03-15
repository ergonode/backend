<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Channel\Domain\Command\ExporterCommandInterface;
use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\AggregateId;

class ProcessOptionCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\Channel\Domain\ValueObject\ExportLineId")
     */
    private ExportLineId $lineId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\AggregateId")
     */
    private AggregateId $optionId;

    public function __construct(ExportLineId $lineId, ExportId $exportId, AggregateId $optionId)
    {
        $this->lineId = $lineId;
        $this->exportId = $exportId;
        $this->optionId = $optionId;
    }

    public function getLineId(): ExportLineId
    {
        return $this->lineId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getOptionId(): AggregateId
    {
        return $this->optionId;
    }
}
