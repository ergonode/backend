<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;

class GenerateUserResetPasswordTokenCommand implements AccountCommandInterface
{
    private UserId $id;

    private string $url;

    public function __construct(UserId $id, string $url)
    {
        $this->id = $id;
        $this->url = $url;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
