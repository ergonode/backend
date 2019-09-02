<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\ValueObject;

use Ergonode\Workflow\Domain\Entity\StatusId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class Transition
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $source;

    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $destination;

    /**
     * @param string   $name
     * @param StatusId $source
     * @param StatusId $destination
     */
    public function __construct(string $name, StatusId $source, StatusId $destination)
    {
        $this->name = $name;
        $this->source = $source;
        $this->destination = $destination;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return StatusId
     */
    public function getSource(): StatusId
    {
        return $this->source;
    }

    /**
     * @return StatusId
     */
    public function getDestination(): StatusId
    {
        return $this->destination;
    }

    /**
     * @param Transition $transition
     *
     * @return bool
     */
    public function isEqual(Transition $transition): bool
    {
        return $transition->getName() === $this->name
            && $transition->getSource()->isEqual($this->source)
            && $transition->getDestination()->isEqual($this->destination);
    }
}
