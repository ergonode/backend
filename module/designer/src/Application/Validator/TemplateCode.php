<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Validator;

use Ergonode\SharedKernel\Application\Validator\SystemCode;

/**
 * @Annotation
 */
class TemplateCode extends SystemCode
{
    public string $minMessage = 'Template code is too short. It should have at least {{ limit }} characters.';

    public string $maxMessage = 'Template code is too long. It should contain {{ limit }} characters or less.';

    public string $validMessage = 'Template code can have only letters, digits or underscore symbol.';
}
