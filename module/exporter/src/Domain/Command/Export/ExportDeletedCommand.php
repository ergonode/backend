<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Exporter\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\ExporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;

class ExportDeletedCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("string")
     */
    private string $channelType;

    public function __construct(ExportId $exportId, string $channelType)
    {
        $this->exportId = $exportId;
        $this->channelType = $channelType;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getChannelType(): string
    {
        return $this->channelType;
    }
}
