<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeFeatureScope;
use Ergonode\Core\Test\Behat\Context\DAMA\StaticDriver;

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
    }

    /**
     * @AfterFeature
     */
    public static function rollback(): void
    {
        StaticDriver::rollBack();
    }
}
