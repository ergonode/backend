<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\AbstractCreateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CreateTextareaAttributeCommand extends AbstractCreateAttributeCommand
{
    /**
     * @var bool
     *
     * @JMS\Type("boolean")
     */
    private bool $richEdit;

    /**
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param bool               $richEdit
     * @param array              $groups
     *
     * @throws \Exception
     */
    public function __construct(
        AttributeCode $code,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        bool $richEdit,
        array $groups = []
    ) {
        parent::__construct(
            $code,
            $label,
            $hint,
            $placeholder,
            $scope,
            $groups
        );

        $this->richEdit = $richEdit;
    }

    /**
     * @return bool
     */
    public function richEdit(): bool
    {
        return $this->richEdit;
    }
}
