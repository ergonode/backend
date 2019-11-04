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
class UpdateNoteCommand
{
    /**
     * @var NoteId $id
     *
     * @JMS\Type("Ergonode\Note\Domain\Entity\NoteId")
     */
    private $id;

    /**
     * @var string $content
     *
     * @JMS\Type("string")
     */
    private $content;

    /**
     * @param NoteId $id
     * @param string $content
     */
    public function __construct(NoteId $id, string $content)
    {
        $this->id = $id;
        $this->content = $content;
    }

    /**
     * @return NoteId
     */
    public function getId(): NoteId
    {
        return $this->id;
    }

        /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}
