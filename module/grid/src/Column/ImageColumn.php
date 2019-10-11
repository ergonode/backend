<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

/**
 */
class ImageColumn extends AbstractColumn
{
    public const TYPE = 'IMAGE';

    /**
     * @var string
     */
    private $uri;

    /**
     * @param string      $field
     * @param string|null $uri
     */
    public function __construct(string $field, ?string $uri = null)
    {
        parent::__construct($field);
        $this->uri = $uri;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return bool
     */
    public function hasUri(): bool
    {
        return null !== $this->uri;
    }

    /**
     * @return string|null
     */
    public function getUri(): ?string
    {
        return $this->uri;
    }
}
