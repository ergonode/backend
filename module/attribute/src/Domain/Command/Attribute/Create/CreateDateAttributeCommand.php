<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command\Attribute\Create;

use Ergonode\Attribute\Domain\Command\Attribute\AbstractCreateAttributeCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\DateFormat;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;

/**
 */
class CreateDateAttributeCommand extends AbstractCreateAttributeCommand
{
    /**
     * @var DateFormat
     *
     * @JMS\Type("Ergonode\Attribute\Domain\ValueObject\DateFormat")
     */
    private DateFormat $format;

    /**
     * @param AttributeCode      $code
     * @param TranslatableString $label
     * @param TranslatableString $hint
     * @param TranslatableString $placeholder
     * @param AttributeScope     $scope
     * @param DateFormat         $format
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
        DateFormat $format,
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

        $this->format = $format;
    }

    /**
     * @return DateFormat
     */
    public function getFormat(): DateFormat
    {
        return $this->format;
    }
}
