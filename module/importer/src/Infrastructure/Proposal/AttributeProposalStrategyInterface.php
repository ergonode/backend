<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Infrastructure\Proposal;

interface AttributeProposalStrategyInterface
{
    /**
     * @param array $values
     */
    public function support(string $name, array $values): bool;

    public function getTypeProposal(): string;
}
