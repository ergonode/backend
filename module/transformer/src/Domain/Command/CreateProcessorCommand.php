<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Domain\Command;

use Ergonode\Importer\Domain\Entity\ImportId;
use Ergonode\Transformer\Domain\Entity\ProcessorId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class CreateProcessorCommand implements DomainCommandInterface
{
    /**
     * @var ProcessorId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\ProcessorId")
     */
    private $id;

    /**
     * @var ImportId
     *
     * @JMS\Type("Ergonode\Importer\Domain\Entity\ImportId")
     */
    private $importId;

    /**
     * @var TransformerId
     *
     * @JMS\Type("Ergonode\Transformer\Domain\Entity\TransformerId")
     */
    private $transformerId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $action;

    /**
     * @param ImportId      $importId
     * @param TransformerId $transformerId
     * @param string        $action
     *
     * @throws \Exception
     */
    public function __construct(ImportId $importId, TransformerId $transformerId, string $action)
    {
        $this->id = ProcessorId::generate();
        $this->importId = $importId;
        $this->transformerId = $transformerId;
        $this->action = $action;
    }

    /**
     * @return ProcessorId
     */
    public function getId(): ProcessorId
    {
        return $this->id;
    }

    /**
     * @return ImportId
     */
    public function getImportId(): ImportId
    {
        return $this->importId;
    }

    /**
     * @return TransformerId
     */
    public function getTransformerId(): TransformerId
    {
        return $this->transformerId;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }
}
