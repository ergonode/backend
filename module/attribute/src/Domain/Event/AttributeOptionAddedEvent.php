<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Event;

use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class AttributeOptionAddedEvent implements DomainEventInterface
{
    /**
     * @var OptionKey
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionKey")
     */
    private $key;

    /**
     * @var OptionInterface
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\OptionValue\AbstractOption")
     */
    private $option;

    /**
     * @param OptionKey       $key
     * @param OptionInterface $option
     */
    public function __construct(OptionKey $key, OptionInterface $option)
    {
        $this->key = $key;
        $this->option = $option;
    }

    /**
     * @return OptionKey
     */
    public function getKey(): OptionKey
    {
        return $this->key;
    }

    /**
     * @return OptionInterface
     */
    public function getOption(): OptionInterface
    {
        return $this->option;
    }
}
