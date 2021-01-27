<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ImporterErgonode1\Infrastructure\Reader;

use Ergonode\ImporterErgonode1\Infrastructure\Model\AttributeModel;

class ErgonodeAttributeReader extends AbstractErgonodeReader
{
    /**
     * @throws \JsonException
     */
    public function read(): ?AttributeModel
    {
        $item = null;

        while ($this->records->valid()) {
            $record = $this->records->current();

            if (null === $item) {
                $item = new AttributeModel(
                    $record['_id'],
                    $record['_code'],
                    $record['_type'],
                    $record['_scope']
                );
            } elseif ($item->getId() !== $record['_id']) {
                break;
            }

            if (!empty($record['_name'])) {
                $item->addName($record['_language'], $record['_name']);
            }
            if (!empty($record['_hint'])) {
                $item->addHint($record['_language'], $record['_hint']);
            }
            if (!empty($record['_placeholder'])) {
                $item->addPlaceholder($record['_language'], $record['_placeholder']);
            }
            $this->mapParameters($item, $record['_parameters']);

            $this->records->next();
        }

        return $item;
    }

    /**
     * @throws \JsonException
     */
    private function mapParameters(AttributeModel $model, string $parameters): void
    {
        $parameters = json_decode($parameters, true, 512, JSON_THROW_ON_ERROR);
        foreach ($parameters as $key => $value) {
            $model->addParameter($key, $value);
        }
    }
}
