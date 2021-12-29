<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\Command;

use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\SharedKernel\Domain\DomainCommandInterface;

class ReprocessBatchActionCommand extends AbstractPayloadCommand implements DomainCommandInterface
{
    private BatchActionId $id;

    private bool $autoEndOnErrors;

    /**
     * @param mixed $payload
     */
    public function __construct(
        BatchActionId $id,
        bool $autoEndOnErrors,
        $payload = null
    ) {
        $this->id = $id;
        $this->autoEndOnErrors = $autoEndOnErrors;
        parent::__construct($payload);
    }

    public function getId(): BatchActionId
    {
        return $this->id;
    }

    public function isAutoEndOnErrors(): bool
    {
        return $this->autoEndOnErrors;
    }
}
