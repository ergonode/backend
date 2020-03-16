<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;

/**
 */
class TranslatableColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    /**
     * @var string|null
     */
    private ?string $parameters;

    /**
     * @var string|null
     */
    private ?string $domain;

    /**
     * @param string      $field
     * @param string      $label
     * @param string|null $parameters
     * @param string|null $domain
     */
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

    /**
     * @return string|null
     */
    public function getParameters(): ?string
    {
        return $this->parameters;
    }

    /**
     * @return string|null
     */
    public function getDomain(): ?string
    {
        return $this->domain;
    }
}
