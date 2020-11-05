<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class BatchActionFormModel
{
    /**
     * @Assert\NotBlank(message="Batch action type is required")
     * @Assert\Length(
     *     max=20,
     *     maxMessage="Batch action type is too long. It should contain {{ limit }} characters or less."
     * )
     */
    public string $type;

    /**
     * @var array
     *
     * @Assert\Count(min=1),
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Uuid(strict=true)
     * })
     */
    public array $ids = [];
}
