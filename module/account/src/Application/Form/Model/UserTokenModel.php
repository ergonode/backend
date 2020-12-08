<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Form\Model;

use Ergonode\Account\Application\Validator\Constraints\AvailableTokenConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class UserTokenModel
{
    /**
     * @Assert\NotBlank()
     *
     * @AvailableTokenConstraint()
     */
    public ?string $token;
}
