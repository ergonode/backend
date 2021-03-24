<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

class BatchActionFilterIdsFormModel
{
    /**
     * @var string[]
     *
     * @Assert\Count(min=1),
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true)
     * })
     *
     * @JMS\Type("array")
     */
    public array $list = [];

    /**
     * @Assert\Type(type="bool")
     *
     * @JMS\Type("bool")
     */
    public ?bool $included = null;
}
