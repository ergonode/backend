<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

/**
 */
class ImageColumn extends AbstractColumn
{
    public const TYPE = 'IMAGE';
    private const WIDTH = 100;

    /**
     * @var string
     */
    private $uri;

    /**
     * @param string      $field
     * @param string|null $uri
     */
    public function __construct(string $field, string $uri = null)
    {
        parent::__construct($field);
        $this->uri = $uri;

        $this->setWidth(self::WIDTH);
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
     * @return string|null
     */
    public function render(string $id, array $row): ?string
    {
        $image = $row[$id];
        if ($this->uri && $image) {
            return sprintf('%s/%s', $this->uri, $image);
        }

        return $image;
    }
}
