<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CreateAttributeGroupCommand implements DomainCommandInterface
{
    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId")
     */
    private AttributeGroupId $id;

    /**
     * @var AttributeGroupCode
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode")
     */
    private AttributeGroupCode $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @param AttributeGroupCode $code
     * @param TranslatableString $name
     *
     * @throws \Exception
     */
    public function __construct(AttributeGroupCode $code, TranslatableString $name)
    {
        $this->id = AttributeGroupId::generate();
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * @return AttributeGroupId
     */
    public function getId(): AttributeGroupId
    {
        return $this->id;
    }

    /**
     * @return AttributeGroupCode
     */
    public function getCode(): AttributeGroupCode
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
