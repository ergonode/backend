<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\TranslationDeepl\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\TranslationDeepl\Domain\Entity\TranslationDeepl;
use Ergonode\TranslationDeepl\Domain\Entity\TranslationDeeplId;

/**
 */
interface TranslationDeeplRepositoryInterface
{
    /**
     * @param TranslationDeeplId $id
     *
     * @return AbstractAggregateRoot|TranslationDeepl
     */
    public function load(TranslationDeeplId $id): ?AbstractAggregateRoot;

    /**
     * @param AbstractAggregateRoot $aggregateRoot
     */
    public function save(AbstractAggregateRoot $aggregateRoot): void;

    /**
     * @param TranslationDeeplId $id
     *
     * @return bool
     */
    public function exists(TranslationDeeplId $id): bool;
}
