<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Domain\Repository;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Entity\NoteId;

/**
 */
interface NoteRepositoryInterface
{
    /**
     * @param NoteId $id
     *
     * @return AbstractAggregateRoot
     */
    public function load(NoteId $id): AbstractAggregateRoot;

    /**
     * @param Note $object
     */
    public function save(Note $object): void;

    /**
     * @param NoteId $id
     *
     * @return bool
     */
    public function exists(NoteId $id): bool;
}
