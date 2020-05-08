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
class ChannelCreateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(),
     * @Assert\Length(max=32, maxMessage="Channel name is to long, It should have {{ limit }} character or less.")
     */
    public ?string $name;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Sgement is required")
     */
    public ?string $exportProfileId;

    /**
     */
    public function __construct()
    {
        $this->name = null;
        $this->exportProfileId = null;
    }
}
