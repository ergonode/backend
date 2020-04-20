<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Model\Attribute;

use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Infrastructure\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class AttributeFormModel
{
    /**
     * @var AttributeCode
     *
     * @Assert\NotBlank(message="Attribute code is required", groups={"Create"})
     * @Assert\Length(max=128, groups={"Create"})
     * @AppAssert\AttributeCode(groups={"Create"})
     */
    public ?AttributeCode $code = null;

    /**
     * @var bool
     */
    public bool $multilingual = true;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=32,
     *      maxMessage="Attribute name is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $label = [];

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=4000,
     *       maxMessage="Attribute placeholder is to long. It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $placeholder = [];

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=4000,
     *       maxMessage="Attribute hint is to long. It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $hint = [];

    /**
     * @var array
     */
    public array $groups = [];
}
