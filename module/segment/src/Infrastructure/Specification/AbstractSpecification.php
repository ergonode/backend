<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Specification;

use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\Discriminator(
 *     field = "type",
 *     map = {
 *         "attribute_exists": "Ergonode\Segment\Infrastructure\Specification\AttributeExistsSpecification",
 *         "attribute_value": "Ergonode\Segment\Infrastructure\Specification\AttributeValueSpecification",
 *     }
 * )
 */
abstract class AbstractSpecification
{
}
