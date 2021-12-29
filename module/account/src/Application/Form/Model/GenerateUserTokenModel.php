<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator as AccountAssert;
use Symfony\Component\Validator\Constraints as Assert;

class GenerateUserTokenModel
{
    /**
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(mode="strict")
     */
    public ?string $email = null;

    /**
     * @Assert\NotBlank(message="URL is required")
     * @Assert\Url()
     * @AccountAssert\HostAvailable()
     */
    public ?string $url = null;
}
