<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Mapper;

class SnakeCaseMapper
{
    /**
     * @param array $array
     *
     * @return array
     */
    public function map(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $key = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            $result[$key] = $value;
        }

        return $result;
    }
}
