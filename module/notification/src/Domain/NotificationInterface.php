<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

interface NotificationInterface
{
    public function getCreatedAt(): \DateTime;

    public function getMessage(): string;

    public function getAuthorId(): ?UserId;

    /**
     * @return string[]
     */
    public function getParameters(): array;
}
