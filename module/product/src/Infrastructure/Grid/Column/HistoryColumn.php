<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Grid\Column;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\Column\AbstractColumn;

class HistoryColumn extends AbstractColumn
{
    public const TYPE = 'TEXT';

    private string $parameterField;

    public function __construct(string $logField, string $parameterField, string $label, Language $language)
    {
        parent::__construct($logField, $label);
        $this->setLanguage($language);
        $this->parameterField = $parameterField;
    }

    public function getType(): string
    {
        return self::TYPE;
    }

    public function getParameterField(): string
    {
        return $this->parameterField;
    }
}
