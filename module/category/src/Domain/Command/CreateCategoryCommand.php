<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

class CreateCategoryCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
    private CategoryId $id;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private CategoryCode $code;

    /**
     * @throws \Exception
     */
    public function __construct(CategoryId $id, CategoryCode $code, TranslatableString $name)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getCode(): CategoryCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
