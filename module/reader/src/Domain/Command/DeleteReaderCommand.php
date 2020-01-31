<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\Reader\Domain\Entity\ReaderId;

/**
 */
class DeleteReaderCommand implements DomainCommandInterface
{
    /**
     * @var ReaderId
     *
     * @JMS\Type("Ergonode\Reader\Domain\Entity\ReaderId")
     */
    private ReaderId $id;

    /**
     * @param ReaderId $id
     */
    public function __construct(ReaderId $id)
    {
        $this->id = $id;
    }

    /**
     * @return ReaderId
     */
    public function getId(): ReaderId
    {
        return $this->id;
    }
}
