<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Exception;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Core\Domain\ValueObject\Language;

class MaxImportedRelationsExceededException extends ImportException
{
    private const MESSAGE = 'Given {current} relations for the attribute {code} exceed'
    .' permitted {max} items for language {language}';

    public function __construct(
        AttributeCode $code,
        Language $language,
        int $current,
        int $max,
        \Throwable $previous = null
    ) {
        parent::__construct(self::MESSAGE, [
            '{code}' => $code->getValue(),
            '{max}' => $max,
            '{current}' => $current,
            '{language}' => $language,
        ], $previous);
    }
}
