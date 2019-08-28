<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\Transformer\Domain\Entity\ProcessorId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class StartProcessImportLineCommand
{
    /**
     * @var ProcessorId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\ProcessorId")
     */
    private $id;

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
