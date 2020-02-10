<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class GenerateSegmentCommand implements DomainCommandInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $code;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $type;

    /**
     * @param string $code
     * @param string $type
     *
     * @throws \Exception
     */
    public function __construct(string $code, string $type)
    {
        $this->id = SegmentId::generate();
        $this->code = $code;
        $this->type = $type;
    }

    /**
     * @return SegmentId
     */
    public function getId(): SegmentId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
