<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Domain\ValueObject\ResetToken;
use JMS\Serializer\Annotation as JMS;

class ApplyUserResetTokenCommand implements AccountCommandInterface
{
    /**
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\ResetToken")
     */
    private ResetToken $token;

    /**
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\Password")
     */
    private Password $password;

    public function __construct(ResetToken $token, Password $password)
    {
        $this->token = $token;
        $this->password = $password;
    }

    public function getToken(): ResetToken
    {
        return $this->token;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }
}
