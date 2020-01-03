<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Importer\Domain\Entity\ImportLineId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CreateImportLineCommand implements DomainCommandInterface
{
    /**
     * @var ImportLineId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportLineId")
     */
    private $id;

    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private $importId;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private $row;

    /**
     * @param ImportLineId $id
     * @param ImportId     $importId
     * @param array        $row
     */
    public function __construct(ImportLineId $id, ImportId $importId, array $row)
    {
        $this->id = $id;
        $this->importId = $importId;
        $this->row = $row;
    }

    /**
     * @return ImportLineId
     */
    public function getId(): ImportLineId
    {
        return $this->id;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return array
     */
    public function getCollection(): array
    {
        return $this->row;
    }
}
