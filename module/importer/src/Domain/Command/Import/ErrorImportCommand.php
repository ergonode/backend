<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Importer\Domain\ValueObject\Progress;

/**
 */
class ErrorImportCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ImportId")
     */
    private ImportId $id;

    /**
     * @var Progress
     *
     * @JMS\Type("Ergonode\Importer\Domain\ValueObject\Progress")
     */
    private Progress $steps;

    /**
     * @var int
     *
     * @JMS\Type("int")
     */
    private int $line;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $message;

    /**
     * @param ImportId $id
     * @param Progress $steps
     * @param int      $line
     * @param string   $message
     */
    public function __construct(ImportId $id, Progress $steps, int $line, string $message)
    {
        $this->id = $id;
        $this->steps = $steps;
        $this->line = $line;
        $this->message = $message;
    }

    /**
     * @return ImportId
     */
    public function getId(): ImportId
    {
        return $this->id;
    }

    /**
     * @return Progress
     */
    public function getSteps(): Progress
    {
        return $this->steps;
    }

    /**
     * @return int
     */
    public function getLine(): int
    {
        return $this->line;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
