<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Proposal\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\AbstractNumericAttribute;
use Ergonode\Importer\Infrastructure\Proposal\AttributeProposalStrategyInterface;

/**
 */
class NumericProposalStrategy implements AttributeProposalStrategyInterface
{
    /**
     * @param string $name
     * @param array  $values
     *
     * @return bool
     */
    public function support(string $name, array $values): bool
    {
        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getTypeProposal(): string
    {
        return AbstractNumericAttribute::TYPE;
    }
}
