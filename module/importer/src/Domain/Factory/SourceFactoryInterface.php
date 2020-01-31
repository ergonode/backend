<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Factory;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Domain\Entity\Source\SourceId;

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
     * @param string   $filename
     *
     * @return AbstractSource
     */
    public function create(SourceId $sourceId, string $filename): AbstractSource;
}
