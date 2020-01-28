<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Importer\Domain\Entity\ImportId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ErrorImportCommand implements DomainCommandInterface
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private ImportId $id;

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
     * @param int      $line
     * @param string   $message
     */
    public function __construct(ImportId $id, int $line, string $message)
    {
        $this->id = $id;
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
