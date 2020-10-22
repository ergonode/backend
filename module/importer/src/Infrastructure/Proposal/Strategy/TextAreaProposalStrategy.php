<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Proposal\Strategy;

use Ergonode\Importer\Infrastructure\Proposal\AttributeProposalStrategyInterface;
use Ergonode\Attribute\Domain\Entity\Attribute\AbstractTextareaAttribute;

class TextAreaProposalStrategy implements AttributeProposalStrategyInterface
{
    /**
     * @param array $values
     */
    public function support(string $name, array $values): bool
    {
        foreach ($values as $value) {
            if (mb_strlen($value) > 255) {
                return true;
            }
        }

        return false;
    }

    public function getTypeProposal(): string
    {
        return AbstractTextareaAttribute::TYPE;
    }
}
