<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\Transformer\Domain\Entity\TransformerId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class ProcessImportLineCommand implements DomainCommandInterface
{
    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $transformerId;

    /**
     * @var array
     *
     * @JMS\Type("array")
     */
    private $row;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $action;

    /**
     * @param TransformerId $transformerId
     * @param array         $row
     * @param string        $action
     */
    public function __construct(TransformerId $transformerId, array $row, string $action)
    {
        $this->transformerId = $transformerId;
        $this->row = $row;
        $this->action = $action;
    }

    /**
     * @return TransformerId
     */
    public function getTransformerId(): TransformerId
    {
        return $this->transformerId;
    }

    /**
     * @return array
     */
    public function getRow(): array
    {
        return $this->row;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
