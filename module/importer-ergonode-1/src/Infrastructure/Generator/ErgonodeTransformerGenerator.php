<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Entity\Transformer;
use Ergonode\Importer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;
use Ergonode\ImporterErgonode1\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;

/**
 * @deprecated Transformers will be removed from system
 */
class ErgonodeTransformerGenerator implements TransformerGeneratorStrategyInterface
{
    public function getType(): string
    {
        return ErgonodeZipSource::TYPE;
    }

    /**
     * @throws \Exception
     */
    public function generate(
        TransformerId $transformerId,
        string $name,
        AbstractSource $source
    ): Transformer {
        return new Transformer($transformerId, $name, $name);
    }
}
