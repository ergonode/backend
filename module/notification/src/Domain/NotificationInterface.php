<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Notification\Domain;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Ergonode\SharedKernel\Domain\AbstractId;

interface NotificationInterface
{
    public function getCreatedAt(): \DateTime;

    public function getMessage(): string;

    public function getAuthorId(): ?UserId;

    public function getType(): string;

    public function getObjectId(): ?AbstractId;

    /**
     * @return string[]
     */
    public function getParameters(): array;
}
