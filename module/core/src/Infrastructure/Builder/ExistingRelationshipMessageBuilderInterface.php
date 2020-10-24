<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Builder;

use Ergonode\Core\Infrastructure\Model\RelationshipCollection;

interface ExistingRelationshipMessageBuilderInterface
{
    public function build(RelationshipCollection $relationshipCollection): string;
}
