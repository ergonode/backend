<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Generator;

use Ergonode\Account\Domain\ValueObject\ResetToken;

class DefaultResetTokenGenerator implements ResetTokenGeneratorInterface
{
    /**
     * @throws \Exception
     */
    public function getToken(): ResetToken
    {
        return new ResetToken(bin2hex(random_bytes(64)));
    }
}
