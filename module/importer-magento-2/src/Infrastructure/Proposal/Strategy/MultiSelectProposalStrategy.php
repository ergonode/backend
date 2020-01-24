<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Infrastructure\Proposal\Strategy;

use Ergonode\Attribute\Domain\Entity\Attribute\MultiSelectAttribute;
use Ergonode\ImporterMagento2\Infrastructure\Proposal\AttributeProposalStrategyInterface;

/**
 */
class MultiSelectProposalStrategy implements AttributeProposalStrategyInterface
{
    private const SEPARATOR = '|';

    /**
     * @param string              $name
     * @param array               $values
     *
     * @return bool
     */
    public function support(string $name, array $values): bool
    {
        $hasSeparator = false;

        foreach ($values as $value) {
            if (mb_strpos((string) $value, self::SEPARATOR)) {
                $hasSeparator = true;
            }
        }

        return $hasSeparator;
    }

    /**
     * @return string
     */
    public function getTypeProposal(): string
    {
       return MultiSelectAttribute::TYPE;
    }
}