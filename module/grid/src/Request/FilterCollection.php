<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Request;

/**
 */
class FilterCollection
{
    public const DELIMITER = ';';
    public const COMPARISON = '=';
    public const SEPARATOR = ',';

    public const MAP = [
        self::DELIMITER => '%3B',
        self::COMPARISON => '%3D',
        self::SEPARATOR => '%2C',
    ];

    /**
     * @var array
     */
    private $filters;

    /**
     * @param string|null $string
     */
    public function __construct(string $string = null)
    {
        $this->filters = [];

        if ($string) {
            $filters = explode(self::DELIMITER, $string);
            $comparisonHash = self::MAP[self::COMPARISON];
            foreach ($filters as $filter) {
                $filter = preg_replace('/'.$comparisonHash.'/', self::COMPARISON, $filter, 1);
                $data = explode(self::COMPARISON, $filter);
                if (!empty($data)) {
                    if (!isset($data[1])) {
                        $this->filters[$data[0]] = null;
                    } else {
                        $this->filters[$data[0]] = str_replace(array_values(self::MAP), array_keys(self::MAP), $data[1]);
                    }
                }
            }
        }
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->filters);
    }

    /**
     * @param string      $key
     * @param string|null $default
     *
     * @return string
     */
    public function getString(string $key, ?string $default = null): ?string
    {
        return $this->filters[$key] ?? $default;
    }

    /**
     * @param string $key
     * @param array  $default
     *
     * @return array
     */
    public function getArray(string $key, array $default = []): array
    {
        if (isset($this->filters[$key])) {
            return explode(self::SEPARATOR, $this->filters[$key]);
        }

        return $default;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
