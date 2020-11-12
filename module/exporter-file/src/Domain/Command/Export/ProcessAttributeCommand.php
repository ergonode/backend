<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\ExporterCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class ProcessAttributeCommand implements ExporterCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ExportId")
     */
    private ExportId $exportId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private AttributeId $attributeId;

    public function __construct(ExportId $exportId, AttributeId $attributeId)
    {
        $this->exportId = $exportId;
        $this->attributeId = $attributeId;
    }

    public function getExportId(): ExportId
    {
        return $this->exportId;
    }

    public function getAttributeId(): AttributeId
    {
        return $this->attributeId;
    }
}
