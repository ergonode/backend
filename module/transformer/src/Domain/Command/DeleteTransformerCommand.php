<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

/**
 */
class DeleteTransformerCommand implements DomainCommandInterface
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    /**
     * @param TransformerId $id
     */
    public function __construct(TransformerId $id)
    {
        $this->id = $id;
    }

    /**
     * @return TransformerId
     */
    public function getId(): TransformerId
    {
        return $this->id;
    }
}
