<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Generator;

class CategoryGenerator
{
    public function getHeaders(): array
    {
        return [
            '_code',
            '_name',
            '_language',
        ];
    }

    public function generateCategory(int $repeats): array
    {
        $result = [];
        for ($i = 1; $i <= $repeats; $i++) {
            $number = str_pad((string)$i, 5, '0', STR_PAD_LEFT);
            $key = 'category_'.$number;
            $result[$key] = [
                '_code' => $key,
                '_name' => 'English category name '.$number,
                '_language' => 'en_GB',
            ];
        }

        return $result;
    }
}