<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory\Catalog;

use Ergonode\Exporter\Domain\Entity\Catalog\AbstractExportAttributeValue;
use Ergonode\Exporter\Domain\Entity\Catalog\AttributeValue\DefaultExportAttributeValue;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class AttributeFactory
{

    /**
     * @param string              $key
     * @param ValueInterface|null $attribute
     *
     * @return AbstractExportAttributeValue
     */
    public function create(string $key, ?ValueInterface $attribute = null): AbstractExportAttributeValue
    {
        return new DefaultExportAttributeValue($key, $attribute);
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    public function createList(array $attributes): array
    {
        $result = [];
        foreach ($attributes as $key => $attribute) {
            $result[$key] = $this->create($key, $attribute);
        }

        return $result;
    }
}
