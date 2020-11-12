<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Provider;

use Ergonode\Importer\Infrastructure\Converter\Mapper\ConverterMapperInterface;
use Ergonode\Importer\Infrastructure\Converter\ConverterInterface;

class ConverterMapperProvider
{
    /**
     * @var ConverterMapperInterface[]
     */
    private array $mappers;

    public function __construct(ConverterMapperInterface ...$mappers)
    {
        $this->mappers = $mappers;
    }

    public function provide(ConverterInterface $converter): ConverterMapperInterface
    {
        foreach ($this->mappers as $mapper) {
            if ($mapper->supported($converter)) {
                return $mapper;
            }
        }

        throw new \RuntimeException(sprintf('Can\' find converter mapper for %s converter', $converter->getType()));
    }
}
