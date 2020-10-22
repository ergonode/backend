<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Transformer\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;

interface TransformerGeneratorStrategyInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param TransformerId  $transformerId
     * @param string         $name
     * @param AbstractSource $source
     *
     * @return Transformer
     */
    public function generate(TransformerId $transformerId, string $name, AbstractSource $source): Transformer;
}
