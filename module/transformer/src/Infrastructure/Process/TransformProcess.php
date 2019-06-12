<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Process;

use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Model\Record;
use Ergonode\Transformer\Infrastructure\Action\ImportActionInterface;
use Ergonode\Transformer\Infrastructure\Converter\ConverterInterface;

/**
 */
class TransformProcess
{
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
                $value = $converter->map($record, $field);
                $result->add($collection, $field, $value);
            }
        }

        $action->action($result);
    }
}
