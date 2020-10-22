<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Editor\Domain\Repository;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Editor\Domain\Entity\ProductDraft;

interface ProductDraftRepositoryInterface
{
    public function load(ProductDraftId $id, bool $draft = false): ProductDraft;

    public function save(ProductDraft $aggregateRoot): void;
}
