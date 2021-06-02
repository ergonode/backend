<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;

class TransactionalContext implements Context
{
    /**
     * @AfterFeature
     */
    public static function rollback(): void
    {
        // StaticDriver does begin new transaction on ::connect() method therefore "manual" creation is redundant
        StaticDriver::rollBack();
    }
}
