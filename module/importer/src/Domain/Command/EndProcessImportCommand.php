<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use JMS\Serializer\Annotation as JMS;

/**
 * Class EndProcessImport
 */
class EndProcessImportCommand
{
    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private $importId;

    /**
     * @var null|TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $transformerId;

    /**
     * @var null|string
     *
     * @JMS\Type("string")
     */
    private $action;

    /**
     * @param ImportId      $importId
     * @param TransformerId $transformerId
     * @param string        $action
     */
    public function __construct(ImportId $importId, TransformerId $transformerId = null, string $action = null)
    {
        $this->importId = $importId;
        $this->transformerId = $transformerId;
        $this->action = $action;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return null|TransformerId
     */
    public function getTransformerId(): ?TransformerId
    {
        return $this->transformerId;
    }

    /**
     * @return null|string
     */
    public function getAction(): ?string
    {
        return $this->action;
    }
}
