<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class GenerateUserTokenModel
{
    /**
     * @Assert\NotBlank(message="Email is required")
     * @Assert\Email(mode="strict")
     */
    public ?string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    public ?string $path;
}
