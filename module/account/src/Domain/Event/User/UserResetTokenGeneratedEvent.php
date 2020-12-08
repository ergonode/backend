<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use JMS\Serializer\Annotation as JMS;

class UserResetTokenGeneratedEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $userId;

    /**
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\ResetToken")
     */
    private ResetToken $token;

    /**
     * @JMS\Type("string")
     */
    private string $url;

    public function __construct(UserId $id, ResetToken $token, string $url)
    {
        $this->userId = $id;
        $this->token = $token;
        $this->url = $url;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getToken(): ResetToken
    {
        return $this->token;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
