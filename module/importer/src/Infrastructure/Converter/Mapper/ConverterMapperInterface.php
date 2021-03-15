<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Converter\Mapper;

use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;

interface ConverterMapperInterface
{
    public function supported(ConverterInterface $converter): bool;

    /**
     * @param array $line
     */
    public function map(ConverterInterface $converter, array $line, string $default = null): ?string;
}
