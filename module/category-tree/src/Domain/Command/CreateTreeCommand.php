<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Command;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateTreeCommand
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @param string $name
     * @param string $code
     *
     * @throws \Exception
     */
    public function __construct(string $name, string $code)
    {
        $this->id = CategoryTreeId::fromKey($code);
        $this->name = $name;
        $this->code = $code;
    }

    /**
     * @return CategoryTreeId
     */
    public function getId(): CategoryTreeId
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
