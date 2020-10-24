<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Application\Model\Product\Relation;

use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Segment\Infrastructure\Validator\ValidSegmentId;

class ProductChildBySegmentsFormModel
{
    /**
     * @var string[]|null
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(message="Segment id must be valid uuid format"),
     *
     *     @ValidSegmentId()
     * })
     */
    public ?array $segments = [];
}
