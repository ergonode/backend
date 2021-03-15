<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\NewRelic\Application\NewRelic;

final class NewRelic implements NewRelicInterface
{
    public function startTransaction(?string $license = null): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return $license ?
            newrelic_start_transaction(ini_get('newrelic.appname'), $license) :
            newrelic_start_transaction(ini_get('newrelic.appname'));
    }

    public function endTransaction(bool $ignore = false): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return newrelic_end_transaction($ignore);
    }

    public function nameTransaction(string $name): bool
    {
        if (!$this->isEnabled()) {
            return false;
        }

        return newrelic_name_transaction($name);
    }

    private function isEnabled(): bool
    {
        return extension_loaded('newrelic');
    }
}
