<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Account\Domain\Entity\UserId;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserLanguageChangedEvent implements DomainEventInterface
{
    /**
     * @var UserId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\UserId")
     */
    private $id;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private $from;

    /**
     * @var Language
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Language")
     */
    private $to;

    /**
     * @param UserId   $id
     * @param Language $from
     * @param Language $to
     */
    public function __construct(UserId $id, Language $from, Language $to)
    {
        $this->id = $id;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return UserId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return Language
     */
    public function getFrom(): Language
    {
        return $this->from;
    }

    /**
     * @return Language
     */
    public function getTo(): Language
    {
        return $this->to;
    }
}
