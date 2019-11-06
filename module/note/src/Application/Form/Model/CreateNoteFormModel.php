<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CreateNoteFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Author is required")
     * @Assert\Uuid(strict=true);
     */
    public $authorId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Releted object is required"),
     * @Assert\Uuid(strict=true);
     */
    public $objectId;

    /**
     * @var string
     *
     * @Assert\NotBlank(),
     * @Assert\Length(max=4000, maxMessage="Max note lenght is {max} characters")
     */
    public $content;
}
