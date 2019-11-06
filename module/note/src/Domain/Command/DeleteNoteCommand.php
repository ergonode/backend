<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Domain\Command;

use Ergonode\Note\Domain\Entity\NoteId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteNoteCommand
{
    /**
     * @var NoteId $id
     *
     * @JMS\Type("Ergonode\Note\Domain\Entity\NoteId")
     */
    private $id;

    /**
     * DeleteNoteCommand constructor.
     *
     * @param NoteId $id
     */
    public function __construct(NoteId $id)
    {
        $this->id = $id;
    }

    /**
     * @return NoteId
     */
    public function getId(): NoteId
    {
        return $this->id;
    }
}
