<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TemplateCodeUnique extends Constraint
{
    public string $uniqueMessage = 'Template code is not unique.';
}
