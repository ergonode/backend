<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\FilterInterface;

/**
 */
class TranslatableColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    /**
     * @var string|null
     */
    private $parameters;

    /**
     * @var string|null
     */
    private $domain;

    /**
     * @param string      $field
     * @param string      $label
     * @param Language    $language
     * @param string|null $parameters
     * @param string|null $domain
     */
    public function __construct(
        string $field,
        string $label,
        Language $language,
        string $parameters = null,
        string $domain = null
    ) {
        parent::__construct($field, $label);
        $this->setLanguage($language);
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
