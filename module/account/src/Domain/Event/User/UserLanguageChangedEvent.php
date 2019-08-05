<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Event\User;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UserLanguageChangedEvent implements DomainEventInterface
{
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
     * @param Language $from
     * @param Language $to
     */
    public function __construct(Language $from, Language $to)
    {
        $this->from = $from;
        $this->to = $to;
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
