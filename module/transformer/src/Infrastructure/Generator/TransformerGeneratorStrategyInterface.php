<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Transformer\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;

interface TransformerGeneratorStrategyInterface
{
    public function getType(): string;

    public function generate(TransformerId $transformerId, string $name, AbstractSource $source): Transformer;
}
