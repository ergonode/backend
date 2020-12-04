<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\ValueObject\ResetToken;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\Annotation as JMS;

class UserResetTokenGeneratedEvent implements DomainEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("Ergonode\Account\Domain\ValueObject\ResetToken)
     */
    private ResetToken $token;

    /**
     * @JMS\Type("string")
     */
    private string $path;

    public function __construct(UserId $id, ResetToken $token, string $path)
    {
        $this->id = $id;
        $this->token = $token;
        $this->path = $path;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getToken(): ResetToken
    {
        return $this->token;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
