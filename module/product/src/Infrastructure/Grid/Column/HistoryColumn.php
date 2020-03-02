<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Infrastructure\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\AbstractColumn;

/**
 */
class HistoryColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    /**
     * @var string
     */
    private string $parameterField;

    /**
     * @param string   $logField
     * @param string   $parameterField
     * @param string   $label
     * @param Language $language
     */
    public function __construct(string $logField, string $parameterField, string $label, Language $language)
    {
        parent::__construct($logField, $label);
        $this->setLanguage($language);
        $this->parameterField = $parameterField;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return string
     */
    public function getParameterField(): string
    {
        return $this->parameterField;
    }
}
