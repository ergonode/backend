<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Infrastructure\Generator;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Entity\TransformerId;

/**
 */
interface TransformerGeneratorStrategyInterface
{
    /**
     * @param TransformerId $transformerId
     * @param string        $name
     * @param string        $field
     * @param array         $options
     *
     * @return Transformer
     */
    public function generate(
        TransformerId $transformerId,
        string $name,
        string $field,
        array $options = []
    ): Transformer;

    /**
     * @return string
     */
    public function getType(): string;
}
