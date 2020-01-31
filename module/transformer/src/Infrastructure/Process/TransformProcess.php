<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Process;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\ImportActionInterface;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;
use Ergonode\Transformer\Infrastructure\Provider\ConverterMapperProvider;

/**
 */
class TransformProcess
{
    /**
     * @var ConverterMapperProvider
     */
    private ConverterMapperProvider $provider;

    /**
     * @param ConverterMapperProvider $provider
     */
    public function __construct(ConverterMapperProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param Transformer           $transformer
     * @param ImportActionInterface $action
     * @param array                 $record
     */
    public function process(Transformer $transformer, ImportActionInterface $action, array $record): void
    {
        $result = new Record();

        foreach ($transformer->getConverters() as $collection => $converters) {
            /** @var ConverterInterface $converter */
            foreach ($converters as $field => $converter) {
                $mapper = $this->provider->provide($converter);
                $value = $mapper->map($converter, $record);
                $result->add($collection, $field, $value);
            }
        }

        $action->action($result);
    }
}
