<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Command\User;

use Ergonode\Account\Domain\Command\AccountCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use JMS\Serializer\Annotation as JMS;

class GenerateUserResetPasswordTokenCommand implements AccountCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("string")
     */
    private string $path;

    public function __construct(UserId $id, string $path)
    {
        $this->id = $id;
        $this->path = $path;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
