<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\ValueObject;

/**
 */
class Transition
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Status
     */
    private $source;

    /**
     * @var Status
     */
    private $destination;

    /**
     * @param string $name
     * @param Status $source
     * @param Status $destination
     */
    public function __construct(string $name, Status $source, Status $destination)
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
     * @return Status
     */
    public function getSource(): Status
    {
        return $this->source;
    }

    /**
     * @return Status
     */
    public function getDestination(): Status
    {
        return $this->destination;
    }
}
