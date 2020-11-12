<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Importer\Domain\Entity\Transformer;

interface TransformerGeneratorStrategyInterface
{
    public function getType(): string;

    public function generate(TransformerId $transformerId, string $name, AbstractSource $source): Transformer;
}
