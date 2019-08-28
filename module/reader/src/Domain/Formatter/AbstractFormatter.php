<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Domain\Formatter;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\Discriminator(
 *     field = "type",
 *     map = {
 *         "encoding": "Ergonode\Importer\Infrastructure\Formatter\EncodingFormatter",
 *         "replace": "Ergonode\Importer\Infrastructure\Formatter\ReplaceFormatter",
 *     }
 * )
 */
class AbstractFormatter
{
}
