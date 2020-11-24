<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Application\NewRelic;

final class NewRelic implements NewRelicInterface
{
    public function startTransaction(?string $license = null): bool
    {
        if (!extension_loaded('newrelic')) {
            return false;
        }

        return newrelic_start_transaction(ini_get('newrelic.appname'), $license);
    }

    public function endTransaction(bool $ignore = false): bool
    {
        if (!extension_loaded('newrelic')) {
            return false;
        }

        return newrelic_end_transaction($ignore);
    }

    public function nameTransaction(string $name): bool
    {
        if (!extension_loaded('newrelic')) {
            return false;
        }

        return newrelic_name_transaction($name);
    }
}
