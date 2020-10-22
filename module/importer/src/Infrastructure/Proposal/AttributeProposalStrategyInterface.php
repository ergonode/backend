<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Proposal;

interface AttributeProposalStrategyInterface
{
    /**
     * @param string $name
     * @param array  $values
     *
     * @return bool
     */
    public function support(string $name, array $values): bool;

    /**
     * @return string
     */
    public function getTypeProposal(): string;
}
