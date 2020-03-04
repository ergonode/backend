<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\ValueObject\Progress;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Transformer\Domain\Model\Record;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ProcessImportCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ImportId")
     */
    private ImportId $importId;

    /**
     * @var Progress
     *
     * @JMS\Type("Ergonode\Importer\Domain\ValueObject\Progress")
     */
    private Progress $steps;

    /**
     * @var Progress
     *
     * @JMS\Type("Ergonode\Importer\Domain\ValueObject\Progress")
     */
    private Progress $records;

    /**
     * @var Record
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Model\Record")
     */
    private Record $record;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $action;

    /**
     * @param ImportId $importId
     * @param Progress $steps
     * @param Progress $records
     * @param Record   $record
     * @param string   $action
     */
    public function __construct(ImportId $importId, Progress $steps, Progress $records, Record $record, string $action)
    {
        $this->importId = $importId;
        $this->steps = $steps;
        $this->records = $records;
        $this->record = $record;
        $this->action = $action;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return Progress
     */
    public function getSteps(): Progress
    {
        return $this->steps;
    }

    /**
     * @return Progress
     */
    public function getRecords(): Progress
    {
        return $this->records;
    }

    /**
     * @return Record
     */
    public function getRecord(): Record
    {
        return $this->record;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
