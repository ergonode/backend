<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\SharedKernel\Domain\ValueObject\Email;

/**
 */
class UserId extends AggregateId
{
    public const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    /**
     * @param Email $email
     *
     * @return UserId
     */
    public static function fromEmail(Email $email): UserId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $email->getValue())->getValue());
    }
}
