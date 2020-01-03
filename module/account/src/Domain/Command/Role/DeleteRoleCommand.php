<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\Command\Role;

use Ergonode\Account\Domain\Entity\RoleId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class DeleteRoleCommand implements DomainCommandInterface
{
    /**
     * @var RoleId
     *
     * @JMS\Type("Ergonode\Account\Domain\Entity\RoleId")
     */
    private $id;

    /**
     * @param RoleId $id
     */
    public function __construct(RoleId $id)
    {
        $this->id = $id;
    }

    /**
     * @return RoleId
     */
    public function getId(): RoleId
    {
        return $this->id;
    }
}
