<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Builder;

use Ergonode\Core\Infrastructure\Model\Relationship;

interface ExistingRelationshipMessageBuilderInterface
{
    public function build(Relationship $relationship): string;
}
