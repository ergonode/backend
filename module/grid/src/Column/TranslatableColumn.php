<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Column;

class TranslatableColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    private ?string $parameters;

    private ?string $domain;

    public function __construct(
        string $field,
        string $label,
        string $parameters = null,
        string $domain = null
    ) {
        parent::__construct($field, $label);
        $this->parameters = $parameters;
        $this->domain = $domain;
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    public function getParameters(): ?string
    {
        return $this->parameters;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
