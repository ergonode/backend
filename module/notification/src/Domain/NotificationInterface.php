<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Notification\Domain;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

/**
 */
interface NotificationInterface
{
    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     * @return UserId|null
     */
    public function getAuthorId(): ?UserId;

    /**
     * @return string[]
     */
    public function getParameters(): array;
}
