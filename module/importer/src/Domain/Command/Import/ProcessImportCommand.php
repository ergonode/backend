<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
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
     * @var int
     *
     * @JMS\Type("int")
     */
    private int $line;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private array $row;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $action;

    /**
     * @param ImportId $importId
     * @param int      $line
     * @param array    $row
     * @param string   $action
     */
    public function __construct(ImportId $importId, int $line, array $row, string $action)
    {
        $this->importId = $importId;
        $this->line = $line;
        $this->row = $row;
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
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return array
     */
    public function getRow(): array
    {
        return $this->row;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
