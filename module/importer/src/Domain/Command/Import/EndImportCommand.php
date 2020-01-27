<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command\Import;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class EndImportCommand implements DomainCommandInterface
{
    /**
     * @var ProcessorId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\ProcessorId")
     */
    private ProcessorId $id;

    /**
     * @param ProcessorId $id
     */
    public function __construct(ProcessorId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ProcessorId
     */
    public function getId(): ProcessorId
    {
        return $this->id;
    }
}
