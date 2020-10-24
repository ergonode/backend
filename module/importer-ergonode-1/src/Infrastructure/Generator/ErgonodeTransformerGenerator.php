<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode\Infrastructure\Generator;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\ImporterErgonode\Domain\Entity\ErgonodeZipSource;
use Ergonode\SharedKernel\Domain\Aggregate\TransformerId;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGeneratorStrategyInterface;

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
