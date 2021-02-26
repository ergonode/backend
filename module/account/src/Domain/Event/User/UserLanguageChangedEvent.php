<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use JMS\Serializer\Annotation as JMS;

class UserLanguageChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UserId")
     */
    private UserId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private Language $to;

    public function __construct(UserId $id, Language $to)
    {
        $this->id = $id;
        $this->to = $to;
    }

    public function getAggregateId(): UserId
    {
        return $this->id;
    }

    public function getTo(): Language
    {
        return $this->to;
    }
}
