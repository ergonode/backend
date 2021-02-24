<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Generator;


class AttributeGenerator
{
    public function getHeaders(): array
    {
        return [
            '_code',
            '_type',
            '_language',
            '_name',
            '_hint',
            '_placeholder',
            '_scope',
            'format',
            'currency',
            'rich_edit',
            'unit',
        ];
    }

    public function generateTextAttribute(int $repeats, string $scope): array
    {
        return $this->generateAttribute($repeats, $scope, 'TEXT');
    }

    public function generateNumericAreaAttribute(int $repeats, string $scope): array
    {
        return $this->generateAttribute($repeats, $scope, 'NUMERIC');
    }

    public function generateTextAreaAttribute(int $repeats, string $scope): array
    {
        return $this->generateAttribute($repeats, $scope, 'TEXT_AREA');
    }

    public function generateUnitAttribute(int $repeats, string $scope, string $unit): array
    {
        return $this->generateAttribute($repeats, $scope, 'UNIT', ['unit' => $unit]);
    }

    public function generatePriceAttribute(int $repeats, string $scope, string $currency): array
    {
        return $this->generateAttribute($repeats, $scope, 'UNIT', ['currency' => $currency]);
    }

    private function generateAttribute(int $repeats, string $scope, string $type, array $parameters = []): array
    {
        $result = [];
        for ($i = 1; $i <= $repeats; $i++) {
            $number = str_pad((string)$i, 5, '0', STR_PAD_LEFT);
            $key = $type.'_'.$scope.'_'.$number;
            $result[$key] = [
                '_code' => $key,
                '_type' => $type,
                '_language' => 'en_GB',
                '_name' => 'English label '.$type.' '.$scope.' '.$number,
                '_hint' => 'English hint '.$type.' '.$scope.' '.$number,
                '_placeholder' => 'English placeholder '.$type.' '.$scope.' '.$number,
                '_scope' => $scope,
                'format' => $parameters['format'] ?? null,
                'currency' => $parameters['currency'] ?? null,
                'rich_edit' => $parameters['rich_edit'] ?? null,
                'unit' => $parameters['unit'] ?? null,
            ];
        }

        return $result;
    }
}