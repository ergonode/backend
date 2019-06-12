<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\Importer\Domain\Entity\ImportId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StopProcessImportCommand
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private $importId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $reason;

    /**
     * @param ImportId $importId
     * @param string   $reason
     */
    public function __construct(ImportId $importId, string $reason)
    {
        $this->importId = $importId;
        $this->reason = $reason;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return string
     */
    public function getReason(): string
    {
        return $this->reason;
    }
}
