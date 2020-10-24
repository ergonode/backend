<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class ProcessMultimediaCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private MultimediaId $multimediaId;

    public function __construct(ExportId $exportId, MultimediaId $multimediaId)
    {
        $this->exportId = $exportId;
        $this->multimediaId = $multimediaId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getMultimediaId(): MultimediaId
    {
        return $this->multimediaId;
    }
}
