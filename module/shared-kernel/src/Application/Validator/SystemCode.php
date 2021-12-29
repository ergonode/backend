<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Application\Validator;

use Ergonode\SharedKernel\Domain\AbstractCode;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SystemCode extends Constraint
{
    public string $minMessage = 'System name is too short. It should have at least {{ limit }} characters.';

    public string $maxMessage = 'System name is too long. It should contain {{ limit }} characters or less.';

    public string $validMessage = 'Is not valid code - {{ value }}';

    public int $min = AbstractCode::MIN_LENGTH;

    public int $max = AbstractCode::MAX_LENGTH;
}
