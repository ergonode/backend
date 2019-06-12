<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Grid\FilterInterface;

/**
 * Class TranslatableColumn
 */
class TranslatableColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    /**
     * @var Language
     */
    private $language;

    /**
     * @param string               $field
     * @param string               $label
     * @param Language             $language
     * @param FilterInterface|null $filter
     */
    public function __construct(string $field, string $label, Language $language, ?FilterInterface $filter = null)
    {
        parent::__construct($field, $label, $filter);
        $this->language = $language;
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
     * @return null|string
     */
    public function render(string $id, array $row): ?string
    {
        $string = new TranslatableString(\json_decode($row[$id] ?? '[]', true));

        return $string->get($this->language);
    }
}
