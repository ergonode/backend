<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Test\Behat\Context\DAMA;

use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver as BaseStaticDriver;

class StaticDriver extends BaseStaticDriver
{
    public static function rollBack(): void
    {
        foreach (self::$connections as $connection) {
            if (!$connection->inTransaction()) {// @phpstan-ignore-line
                throw new \LogicException('Trying to rollback non-transactional operation.');
            }
        }

        parent::rollBack();

        foreach (self::$connections as $connection) {
            if ($connection->inTransaction()) {// @phpstan-ignore-line
                throw new \LogicException('Still in transaction after rollback.');
            }
        }
    }
}
