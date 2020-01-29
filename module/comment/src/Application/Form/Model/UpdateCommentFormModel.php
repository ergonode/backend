<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class UpdateCommentFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(),
     * @Assert\Length(max=4000, maxMessage="Comment to long, max length is {{ limit }} characters")
     */
    public ?string $content;

    /**
     * UpdateCommentFormModel constructor.
     */
    public function __construct()
    {
        $this->content = null;
    }
}
