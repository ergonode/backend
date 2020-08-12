<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class UpdateMultimediaCommand implements DomainCommandInterface
{
    /**
     * @var MultimediaId
     */
    private MultimediaId $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var TranslatableString
     */
    private TranslatableString $alt;

    /**
     * @param MultimediaId       $id
     * @param string             $name
     * @param TranslatableString $alt
     */
    public function __construct(MultimediaId $id, string $name, TranslatableString $alt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->alt = $alt;
    }

    /**
     * @return MultimediaId
     */
    public function getId(): MultimediaId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return TranslatableString
     */
    public function getAlt(): TranslatableString
    {
        return $this->alt;
    }
}
