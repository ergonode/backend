<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

class DeleteTransformerCommand implements TransformerCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TransformerId")
     */
    private TransformerId $id;

    public function __construct(TransformerId $id)
    {
        $this->id = $id;
    }

    public function getId(): TransformerId
    {
        return $this->id;
    }
}
