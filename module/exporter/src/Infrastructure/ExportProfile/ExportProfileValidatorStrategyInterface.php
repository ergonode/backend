<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Infrastructure\ExportProfile;

use Symfony\Component\Validator\Constraint;

/**
 */
interface ExportProfileValidatorStrategyInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function supports(string $type): bool;

    /**
     * @param array $data
     *
     * @return Constraint
     */
    public function build(array $data): Constraint;
}
