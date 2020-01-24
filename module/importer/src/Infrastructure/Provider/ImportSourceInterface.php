<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Provider;


use Ergonode\Importer\Domain\Entity\AbstractImport;
use Ergonode\ImporterMagento2\Infrastructure\Configuration\ImportConfiguration;

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
     * @param AbstractImport $import
     *
     * @return ImportConfiguration
     */
    public function process(AbstractImport $import): ImportConfiguration;
}