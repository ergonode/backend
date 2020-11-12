<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\Command\WorkflowCommandInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

class CreateStatusCommand implements WorkflowCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\StatusId")
     */
    private StatusId $id;

    /**
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private StatusCode $code;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\Color")
     */
    private Color $color;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @throws \Exception
     */
    public function __construct(
        StatusCode $code,
        Color $color,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->id = StatusId::fromCode($code->getValue());
        $this->code = $code;
        $this->color = $color;
        $this->name = $name;
        $this->description = $description;
    }

    public function getId(): StatusId
    {
        return $this->id;
    }

    public function getCode(): StatusCode
    {
        return $this->code;
    }

    public function getColor(): Color
    {
        return $this->color;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }
}
