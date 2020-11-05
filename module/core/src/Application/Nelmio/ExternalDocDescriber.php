<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Nelmio;

class ExternalDocDescriber extends \Nelmio\ApiDocBundle\Describer\ExternalDocDescriber
{
    public function __construct($externalDoc, bool $overwrite = false)
    {
        foreach ($externalDoc as $name => $doc) {
            if (substr($name, -strlen('_definitions')) !== '_definitions') {
                continue;
            }

            $externalDoc['definitions'] = array_merge($externalDoc['definitions'] ?? [], $doc);
            unset($externalDoc[$name]);
        }

        parent::__construct($externalDoc, $overwrite);
    }
}
