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
     * @param string               $field
     * @param string               $label
     * @param Language             $language
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, string $label, Language $language, ?FilterInterface $filter = null)
    {
        parent::__construct($field, $label, $filter);
        $this->setLanguage($language);
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
