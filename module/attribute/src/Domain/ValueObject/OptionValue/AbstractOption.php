<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\ValueObject\OptionValue;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\Discriminator(
 *     field = "type",
 *     map = {
 *         "string": "Ergonode\Attribute\Domain\ValueObject\OptionValue\StringOption",
 *         "translation": "Ergonode\Attribute\Domain\ValueObject\OptionValue\MultilingualOption",
 *     }
 * )
 */
abstract class AbstractOption
{
}
