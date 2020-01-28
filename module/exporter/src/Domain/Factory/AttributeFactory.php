<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Factory;

use Ergonode\Exporter\Domain\Entity\AbstractAttribute;
use Ergonode\Exporter\Domain\Entity\Attribute\DefaultAttribute;
use Ergonode\Value\Domain\ValueObject\ValueInterface;

/**
 */
class AttributeFactory
{

    /**
     * @param string              $key
     * @param ValueInterface|null $attribute
     *
     * @return AbstractAttribute
     */
    public function create(string $key, ?ValueInterface $attribute = null): AbstractAttribute
    {
        return new DefaultAttribute($key, $attribute);
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
