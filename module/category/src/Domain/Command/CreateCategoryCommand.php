<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateCategoryCommand implements DomainCommandInterface
{
    /**
     * @var CategoryId
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private CategoryId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var CategoryCode
     *
     * @JMS\Type("Ergonode\Category\Domain\ValueObject\CategoryCode")
     */
    private CategoryCode $code;

    /**
     * @param CategoryCode       $code
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function __construct(CategoryCode $code, TranslatableString $name)
    {
        $this->id = CategoryId::fromCode($code);
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return CategoryId
     */
    public function getId(): CategoryId
    {
        return $this->id;
    }

    /**
     * @return CategoryCode
     */
    public function getCode(): CategoryCode
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
