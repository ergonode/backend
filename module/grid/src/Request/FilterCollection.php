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
    public const GREATER = '>';
    public const LOWER = '<';
    public const SEPARATOR = ',';

    public const MAP = [
        self::DELIMITER => '%3B',
        self::COMPARISON => '%3D',
        self::SEPARATOR => '%2C',
        self::GREATER => '',
        self::LOWER => '',
    ];

    private const REGEXP = '/^(.*?)([!<>=|]=?)(.*?)$/m';

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
            $pattern = sprintf('/%s/', self::MAP[self::COMPARISON]);
            foreach ($filters as $filter) {
                $filter = preg_replace($pattern, self::COMPARISON, $filter, 1);

                if (preg_match_all(self::REGEXP, $filter, $matches)) {
                    $this->filters[$matches[1][0]][$matches[2][0]] = str_replace(array_values(self::MAP), array_keys(self::MAP), $matches[3][0]);
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
     * @param string $key
     *
     * @return array
     */
    public function get(string $key): array
    {
        return array_key_exists($key, $this->filters) ? $this->filters[$key] : [];
    }
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}
