<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Application\Model;

use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class ChannelUpdateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(),
     * @Assert\Length(max=255, maxMessage="Channel name is to long, It should have {{ limit }} character or less.")
     */
    public ?string $name;

    /**
     */
    public function __construct()
    {
        $this->name = null;
    }
}
