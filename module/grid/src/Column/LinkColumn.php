<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

/**
 */
class LinkColumn extends AbstractColumn
{
    public const TYPE = 'LINK';

    /**
     * @var array
     */
    private $links;

    /**
     * @param string $field
     * @param array  $links
     */
    public function __construct(string $field, array $links = [])
    {
        parent::__construct($field);

        $this->links = $links;
        $this->setVisible(false);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @param string $id
     * @param array  $row
     *
     * @return array
     */
    public function render(string $id, array $row): array
    {
        $links = [];

        $mapFunction = static function ($value) {
            return sprintf('{%s}', $value);
        };

        $keys = array_map($mapFunction, array_keys($row));
        $values = array_values($row);

        foreach ($this->links as $name => $link) {
            $links[$name] = str_replace($keys, $values, $link);
        }

        return $links;
    }
}
