<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Command;

use Ergonode\Designer\Domain\Entity\TemplateId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteTemplateCommand
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

    /**
     * @param TemplateId $id
     */
    public function __construct(TemplateId $id)
    {
        $this->id = $id;
    }

    /**
     * @return TemplateId
     */
    public function getId(): TemplateId
    {
        return $this->id;
    }
}
