<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Domain\Factory;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Entity\NoteId;
use Ramsey\Uuid\Uuid;

/**
 */
interface NoteFactoryInterface
{
    /**
     * @param NoteId $id
     * @param UserId $authorId
     * @param Uuid   $objectId
     * @param string $content
     *
     * @return Note
     */
    public function create(NoteId $id, UserId $authorId, Uuid $objectId, string $content): Note;
}
