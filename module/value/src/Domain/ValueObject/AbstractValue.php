<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Value\Domain\ValueObject;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\Discriminator(
 *     field = "type",
 *     map = {
 *         "string": "Ergonode\Value\Domain\ValueObject\StringValue",
 *         "string_collection": "Ergonode\Value\Domain\ValueObject\CollectionValue",
 *         "translation": "Ergonode\Value\Domain\ValueObject\TranslatableStringValue",
 *         "translation_collection": "Ergonode\Value\Domain\ValueObject\TranslatableCollectionValue"
 *     }
 * )
 */
abstract class AbstractValue
{
}
