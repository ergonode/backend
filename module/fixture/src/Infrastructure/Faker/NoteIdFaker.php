<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Note\Domain\Entity\NoteId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class NoteIdFaker extends BaseProvider
{
    /**
     * @return NoteId
     *
     * @throws \Exception
     */
    public function noteId(): NoteId
    {
        return NoteId::generate();
    }
}
