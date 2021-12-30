<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Channel\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ChannelTypeFormModel
{
    /**
     * @Assert\NotBlank(
     *     message="Type of channel is required",
     *     )
     */
    public ?string $type = null;
}
