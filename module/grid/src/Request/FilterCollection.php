<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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

    public const REPLACE = [
        '%3B' => ';',
        '%2C' => ',',
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
            foreach ($filters as $filter) {
                $data = explode(self::COMPARISON, $filter);
                if (!empty($data)) {
                    if (!isset($data[1]) || $data[1] === '') {
                        $this->filters[$data[0]] = null;
                    } else {
                        $this->filters[$data[0]] = str_replace(array_keys(self::REPLACE), array_values(self::REPLACE), $data[1]);
                    }
                }
            }
        }
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
