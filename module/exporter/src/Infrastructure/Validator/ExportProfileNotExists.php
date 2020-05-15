<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ExportProfileNotExists extends Constraint
{
    /**
     * @var string
     */
    public string $message = 'Export Profile Id {{ value }} does not exists';
}
