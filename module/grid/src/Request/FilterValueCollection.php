<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Request;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class FilterValueCollection implements \IteratorAggregate
{
    public const DELIMITER = ';';
    public const LANGUAGE_SEPARATOR = ':';
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
    private array $filters;

    /**
     * @param string|null $string
     */
    public function __construct(string $string = null)
    {
        $this->filters = [];

        if (null !== $string) {
            $filters = explode(self::DELIMITER, $string);
            $pattern = sprintf('/%s/', self::MAP[self::COMPARISON]);
            foreach ($filters as $filter) {
                $filter = preg_replace($pattern, self::COMPARISON, $filter, 1);

                if (preg_match_all(self::REGEXP, $filter, $matches)) {
                    $value = str_replace(array_values(self::MAP), array_keys(self::MAP), $matches[3][0]);
                    if (null === $value || '' === $value) {
                        $value = null;
                    }

                    $columnData = explode(self::LANGUAGE_SEPARATOR, $matches[1][0]);
                    $columnName = $columnData[0];
                    $columnLanguage = null;
                    if (count($columnData) > 1) {
                        $columnLanguage = new Language($columnData[1]);
                    }

                    $this->filters[$matches[1][0]][] =
                        new FilterValue($columnName, $matches[2][0], $value, $columnLanguage);
                }
            }
        }
    }

    /**
     * @return \ArrayIterator|\Traversable|FilterValue[]
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->filters);
    }
}
