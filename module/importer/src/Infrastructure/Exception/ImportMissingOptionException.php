<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

class ImportMissingOptionException extends ImportException
{
    private const MESSAGE = 'Missing {option} option for {attribute} attribute.';

    public function __construct(OptionKey $option, AttributeCode $attribute, \Throwable $previous = null)
    {
        parent::__construct(
            self::MESSAGE,
            ['{option}' => $option->getValue(), '{attribute}' => $attribute->getValue()],
            $previous
        );
    }
}
