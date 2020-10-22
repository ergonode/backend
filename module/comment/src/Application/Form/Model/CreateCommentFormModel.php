<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class CreateCommentFormModel
{
    /**
     * @Assert\NotBlank(message="Releted object is required"),
     * @Assert\Uuid(strict=true);
     */
    public ?string $objectId;

    /**
     * @Assert\NotBlank(),
     * @Assert\Length(max=4000, maxMessage="Comment to long, max length is {{ limit }} characters")
     */
    public ?string $content;

    public function __construct()
    {
        $this->objectId = null;
        $this->content = null;
    }
}
