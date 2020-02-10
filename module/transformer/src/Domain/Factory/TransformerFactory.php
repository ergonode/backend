<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Factory;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

/**
 */
class TransformerFactory
{
    /**
     * @param TransformerId $id
     * @param string        $name
     * @param string        $key
     *
     * @return Transformer
     *
     * @throws \Exception
     */
    public function create(Transformerid $id, string $name, string $key): Transformer
    {
        return new Transformer(
            $id,
            $name,
            $key
        );
    }
}
