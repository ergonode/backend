<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Ergonode\Importer\Infrastructure\Configuration\ImportConfiguration;

/**
 */
interface ImportSourceInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @param string $type
     *
     * @return bool
     */
    public function supported(string $type): bool;

    /**
     * @param AbstractSource $source
     *
     * @return ImportConfiguration
     */
    public function process(AbstractSource $source): ImportConfiguration;
}
