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
    private array $links;

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
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }
}
