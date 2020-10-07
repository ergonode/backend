<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteMultimediaCommand implements DomainCommandInterface
{
    /**
     * @var MultimediaId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\MultimediaId")
     */
    private MultimediaId $id;

    /**
     * @param MultimediaId $id
     */
    public function __construct(MultimediaId $id)
    {
        $this->id = $id;
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
    }
}
