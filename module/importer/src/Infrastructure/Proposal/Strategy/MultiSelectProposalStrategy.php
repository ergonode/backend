<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Proposal\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\Importer\Infrastructure\Proposal\AttributeProposalStrategyInterface;

class MultiSelectProposalStrategy implements AttributeProposalStrategyInterface
{
    private const SEPARATOR = '|';

    /**
     * @param string $name
     * @param array  $values
     *
     * @return bool
     */
    public function support(string $name, array $values): bool
    {
        foreach ($values as $value) {
            if (mb_strpos((string) $value, self::SEPARATOR)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string
     */
    public function getTypeProposal(): string
    {
        return MultiSelectAttribute::TYPE;
    }
}
