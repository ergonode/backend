<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;

class TransactionalContext implements Context
{
    /**
     * @BeforeFeature
     */
    public static function beginTransaction(BeforeFeatureScope $scope): void
    {
        // StaticDriver does begin new transaction on ::connect().
        // The initial call will have empty StaticDriver::$connections and every other will create a transaction
        StaticDriver::beginTransaction();
        var_dump($scope->getFeature()->getFile());
        ob_flush();
    }

    /**
     * @AfterFeature
     */
    public static function rollback(): void
    {
        StaticDriver::rollBack();
    }
}
