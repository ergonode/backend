<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

/**
 */
interface SourceFactoryInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param SourceId $sourceId
     * @param string   $name
     * @param array    $configuration
     *
     * @return AbstractSource
     */
    public function create(SourceId $sourceId, string $name, array $configuration = []): AbstractSource;
}
